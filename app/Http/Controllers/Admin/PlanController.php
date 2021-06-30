<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponser;
use App\Models\Plan;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    use apiResponser;
    public function store(Request $request)
    {
        DB::beginTransaction();

        $data = $request->all();

        $plan = new Plan;
        $plan->title = $data['title'];
        $plan->description = $data['description'];
        $plan->value = $data['value'];
        $plan->max_bots = $data['max_bots'];
        $plan->max_usage = $data['max_usage'];
        $plan->charge_period = $data['charge_period'];
        $plan->balance_saving = $data['balance_saving'];

        $plan->save();

        $pagarme = new PagarmeRequestService;
        $plan_request = $pagarme->createPlan($plan->value, $plan->charge_period, $plan->title, 2, 0);

        if(isset($plan_request['errors'])){
            DB::rollBack();
            $erro = collect($plan_request['errors'])->pluck("messsage");
            return $this->error($erro, 500);
        }

        $plan->external_id = $plan_request['id'];
        $plan->save();

        DB::commit();
        return $this->success($plan);

    }
}
