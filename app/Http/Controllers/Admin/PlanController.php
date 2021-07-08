<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\PagarmeRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value'             => 'required|numeric',
            'title'             => 'required|string',
            "description"       => 'required|string',
            "max_bots"          => 'required|numeric',
            "max_usage"         => 'required|numeric',
            "charge_period"     => 'required|numeric',
            "balance_saving"    => 'required|boolean',
        ], [
            'value' => "valor",
            'title' => "titulo",
            'description' => "descricao",
            'max_bots' => "robos ativos",
            'max_usage' => "maximo de requisicoes",
            'charge_period' => "duracao do plano",
            'balance_saving' => "guarda saldo do mes anterior"
        ]);

        if($validator->fails()) {
            return ResponseHelper::error("Campo(s) inválidos", 500);
        } else {
            DB::beginTransaction();

            $data = $request->all();

            $plan = new Plan;
            $plan->title = $data['title'];
            $plan->description = $data['description'];
            $plan->value = $data['value'];
            $plan->max_bots = 0 + $data['max_bots'];
            $plan->max_usage = 0 + $data['max_usage'];
            $plan->charge_period = $data['charge_period'];
            $plan->balance_saving = $data['balance_saving'];

            $plan->save();

            $pagarme = new PagarmeRequestService;

            try{
                Log::debug("Criando plano...");
                $plan_request = $pagarme->createPlan(
                    $plan->value * 100, 
                    $plan->charge_period, 
                    $plan->title, 
                    2, 
                    0
                );
                ResponseHelper::log($plan_request, "Plano criado");

                $plan->external_id = $plan_request->id;
                $plan->save();
            } catch(Exception $e) {

                DB::rollBack();
                // $erro = collect($plan_request->errors)->pluck("messsage");
                $erro = "Não foi possível gerar plano";
                return ResponseHelper::error($erro, 500);
            }

            DB::commit();
            return ResponseHelper::success($plan);
        }
    }

    public function show(Plan $plan){
        return $plan 
        ? ResponseHelper::success($plan, __("Retornando plano")) 
        : ResponseHelper::error(__("Plano não encontrado"), 404);
    }

    
    public function update(Request $request, Plan $plan){
        
        $data = [];
        
        if(!empty($request->all()))
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'title'         => 'string',
            'description'   => 'string',
            'charge_period' => 'in:1,30,60,90,365',
            'value'         => 'decimal',
            'max_usage'     => 'integer',
            'max_bots'      => 'integer',
            'balance_saving'=> 'boolean',
        ]);
        
        extract($data);
        
        if($validator->fails()){
            return ResponseHelper::error(__("Campos inválidos"), 500);
        }
        
        $plan->title = !empty($title) ? $title : $plan->title;
        $plan->description = !empty($description) ? $description : $plan->description;
        $plan->charge_period = !empty($charge_period) ? $charge_period : $plan->charge_period;
        $plan->value = !empty($value) ? $value : $plan->value;
        $plan->max_usage = !empty($max_usage) ? $max_usage : $plan->max_usage;
        $plan->max_bots = !empty($max_bots) ? $max_bots : $plan->max_bots;
        $plan->balance_saving = !empty($balance_saving) ? $balance_saving : $plan->balance_saving;

        ResponseHelper::log($data, "Editando plano [ {$plan->id} ]");
        
        $plan->save();
        
        return ResponseHelper::success($plan, __("Plano editado"));
    }

    public function toggleActive(Plan $plan){

    }
}
