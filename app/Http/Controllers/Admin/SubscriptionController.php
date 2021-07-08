<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(env("ITEMS_PER_PAGE", 10));

        return ResponseHelper::success($subscriptions, __("Retornando Assinaturas"));
    }

    public function cancel(Subscription $subscription){
        $pagarme = new PagarmeRequestService;

        $return = $pagarme->cancelSubscription($subscription);
        if(!empty($return['errors'])){
            return ResponseHelper::error($return['errors'], 500);
        }
        return ResponseHelper::success($subscription, __("Assinatura cancelada com sucesso"));
    }

    /**
     * Display the specified resource.
     *
     * @param  Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(int $subscription)
    {
        $sub = Subscription::with("plan")->find($subscription);
        return $sub
            ? ResponseHelper::success($sub, __("Retornando assinatura")) 
            : ResponseHelper::error(__("Assinatura não encontrada"), 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
