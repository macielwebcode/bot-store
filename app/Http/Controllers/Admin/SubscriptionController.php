<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\apiResponser;
use App\Models\Subscription;
use App\Services\PagarmeRequestService;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use apiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(env("ITEMS_PER_PAGE", 10));

        return $this->success($subscriptions, __("Retornando Assinaturas"));
    }

    public function cancel(Subscription $subscription){
        $pagarme = new PagarmeRequestService;

        $return = $pagarme->cancelSubscription($subscription);
        var_dump($return);
        die;
        if(!empty($return['errors'])){
            return $this->error($return['errors'], 500);
        }
        return $this->success($subscription, __("Assinatura cancelada com sucesso"));
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
            ? $this->success($sub, __("Retornando assinatura")) 
            : $this->error(__("Assinatura não encontrada"), 404);
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
