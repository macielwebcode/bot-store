<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponser;
use App\Models\Plan;
use App\Services\PagarmeRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    use apiResponser;
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
            return $this->error("Campo(s) inválidos", 500);
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
                Log::info("Plano criado: [ " . json_encode($plan_request) . " ]");

                $plan->external_id = $plan_request->id;
                $plan->save();
            } catch(Exception $e) {

                DB::rollBack();
                // $erro = collect($plan_request->errors)->pluck("messsage");
                $erro = "Não foi possível gerar plano";
                return $this->error($erro, 500);
            }

            DB::commit();
            return $this->success($plan);
        }
    }
}
