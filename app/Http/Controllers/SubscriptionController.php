<?php

namespace App\Http\Controllers;

use App\Http\Handlers\BalanceHandler;
use App\Http\Traits\apiResponser;
use App\Http\Handlers\InvoiceHandler;
use App\Models\Plan;
use App\Models\User;
use App\Mail\SubscriptionCreated;
use App\Services\PagarmeRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    
    use apiResponser, InvoiceHandler, BalanceHandler;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);
        $subscriptions = $user->subscriptions->toArray();

        return $this->success($subscriptions);
    }
    /**
     * Display a search of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // if($request->get(""))
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $message = "";
        $validated = true;
        $request->validate([
            'amount'            => 'required|numeric',
            'plan_id'           => 'required|integer',
            "holder_name"       => 'required_without:card_id',
            "number"            => 'required_without:card_id',
            "expiration_date"   => 'required_without:card_id',
            "cvv"               => 'required_without:card_id',
        ]);


        $pagarme = new PagarmeRequestService;

        if(!$validated){
            $message = "Dados inválidos";
        } else {

            extract($request->all());

            $plan = Plan::find($plan_id);

            $user_id = Auth::user();
            $user = User::find($user_id);

            if(empty($plan->external_id)) {
                return $this->error("Plano inválido. Verifique com o administrador", 500);
            }

            if(empty($user->pagarme_id)) {
                $phone_numbers = [ $user->phone ];
                $documents = [
                    'type' => 'cpf',
                    'number' => $user->cpf
                ];

                $customer = $pagarme->createCustomer($user->name, $user->email, $user->id, $phone_numbers, $documents);
            } else {
                $customer = $pagarme->getCustomer($user->pagarme_id);
            }

            if(isset($customer['errors'])) {
                $erro = collect($customer['errors'])->pluck("messsage");
                return $this->error($erro, 500);
            }

            if(empty($card_id)) {
                $card = $pagarme->createCreditCard($customer['id'], $number, $expiration_date, $holder_name, $cvv);
                if(isset($card['errors'])){
                    $erro = collect($card['errors'])->pluck("message");
                    return $this->error($erro, 500);
                }

                $card_id = $card['id'];
            }

            $subscription = $pagarme->createSubscription($customer, $plan->pagarme_id, 'credit_card', $card_id);

            if(isset($subscription['errors'])) {
                $erro = collect($subscription['errors'])->pluck("messsage");
                return $this->error($erro, 500);
            }

            // Persist data 
            DB::beginTransaction();

            try {

                // create credit card
                $user->usercards()->create([
                    "card_id" => $card['id'],
                    "brand"   => $card['brand'],
                    "last_digits"   => $card['last_digits'],
                    "holder_name"   => $card['holder_name'],
                ]);

                // create subscription
                $user->subscriptions()->create([
                    "pagarme_id" => $subscription['id'],
                    "plan_id"    => $plan_id,
                    "status"     => $subscription['status']
                ]);

                // create transaction
                $user->transactions()->create([
                    'pagarme_id' => $subscription['current_transaction']['transaction_code'],
                    'status' => $subscription['current_transaction']['status'],
                    'authorization_code' => $subscription['current_transaction']['authorization_code'],
                    'amount' => $subscription['current_transaction']['amount'],
                    'authorized_amount' => $subscription['current_transaction']['authorized_amount'],
                    'paid_amount' => $subscription['current_transaction']['paid_amount'],
                    'refunded_amount' => $subscription['current_transaction']['refunded_amount'],
                    'installments' => $subscription['current_transaction']['installments'],
                    'cost' => $subscription['current_transaction']['cost'],
                    'subscription_code' => $subscription['current_transaction']['subscription_code'],
                    'postback_url' => $subscription['current_transaction']['postback_url'],
                    'card_holder_name' => $subscription['current_transaction']['card_holder_name'],
                    'card_last_digits' => $subscription['current_transaction']['card_last_digits'],
                    'card_first_digits' => $subscription['current_transaction']['card_first_digits'],
                    'card_brand' => $subscription['current_transaction']['card_brand'],
                    'payment_method' => $subscription['current_transaction']['payment_method'],
                ]);

                $user->extracts()->create([
                    "user_id" => $user->id,
                    "amount" => $plan->value,
                    "operation" => "C",
                    "description" => "Início Assinatura - " . $plan->title,
                ]);

                Mail::to($user->email)->send(new SubscriptionCreated($invoice));

                DB::commit();
                return $this->success($subscription);

            } catch(Exception $e) {
                $message = $e->getMessage();
            }
            DB::rollBack();
        }

        return $this->error($message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
