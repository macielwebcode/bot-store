<?php

namespace App\Http\Controllers;

use App\Http\Handlers\BalanceHandler;
use App\Http\Traits\apiResponser;
use App\Models\Plan;
use App\Models\User;
use App\Mail\SubscriptionCreated;
use App\Models\Subscription;
use App\Services\PagarmeRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    
    use apiResponser, BalanceHandler;
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
        $validated = $request->validate([
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

            $user_id = Auth::user()->id;
            $user = User::find($user_id);

            if(!empty($user->subscriptions())){
                return $this->error("Cliente já possui assinatura ativa", 403);
            }

            if(empty($plan->external_id)) {
                return $this->error("Plano inválido. Verifique com o administrador", 500);
            }

            // Persist data 
            try {

                if(empty($user->pagarme_id)) {
                    $phone_numbers = [ $user->phone ];
                    $documents = (object)[
                        'type' => 'cpf',
                        'number' => $user->cpf
                    ];

                    $customer = $pagarme->createCustomer($user->name, $user->email, $user->id, $phone_numbers, [$documents]);
                    $user->pagarme_id = $customer->id;
                } else {
                    $customer = $pagarme->getCustomer($user->pagarme_id);
                }

                if(isset($customer->errors)) {
                    $erro = collect($customer->errors)->pluck("messsage");
                    return $this->error($erro, 500);
                }

                if(empty($card_id)) {
                    $card = $pagarme->createCreditCard($customer->id, $number, $pagarme->dataToPagarme($expiration_date), $holder_name, $cvv);
                    if(isset($card->errors)){
                        $erro = collect($card->errors)->pluck("message");
                        return $this->error($erro, 500);
                    }
                    // create credit card
                    $user->usercards()->create([
                        "card_id" => $card->id,
                        "brand"   => $card->brand,
                        "last_digits"   => $card->last_digits,
                        "holder_name"   => $card->holder_name,
                    ]);

                    $card_id = $card->id;
                }


                $pagarme->setFullAddress($user->street, $user->number, $user->district, $user->cep, $user->country, $user->state, $user->city, $user->complement);

                $number_array = explode(" ", $user->phone);

                $pagarme->setPhones([ 
                    "ddd" => $number_array[1],
                    "number" => $number_array[2] . $number_array[3]
                ]);

                $subscription = $pagarme->createSubscription((Array)$customer, $plan->external_id, 'credit_card', $card_id);

                if(isset($subscription->errors)) {
                    $erro = collect($subscription->errors)->pluck("messsage");
                    return $this->error($erro, 500);
                }

                DB::beginTransaction();

                // create subscription
                $sended_mail = $user->subscriptions()->create([
                    "pagarme_id" => $subscription->id,
                    "plan_id"    => $plan_id,
                    "status"     => $subscription->status
                ]);

                // create transaction
                if(isset($subscription->current_transaction->id)){
                    $user->transactions()->create([
                        'pagarme_id' => $subscription->current_transaction->id,
                        'user_id' => $user->id,
                        'status' => $subscription->current_transaction->status,
                        'authorization_code' => $subscription->current_transaction->authorization_code,
                        'amount' => $subscription->current_transaction->amount,
                        'authorized_amount' => $subscription->current_transaction->authorized_amount,
                        'paid_amount' => $subscription->current_transaction->paid_amount,
                        'refunded_amount' => $subscription->current_transaction->refunded_amount,
                        'installments' => $subscription->current_transaction->installments,
                        'cost' => $subscription->current_transaction->cost,
                        'subscription_id' => $subscription->current_transaction->subscription_id,
                        'postback_url' => $subscription->current_transaction->postback_url,
                        'card_holder_name' => $subscription->current_transaction->card_holder_name,
                        'card_last_digits' => $subscription->current_transaction->card_last_digits,
                        'card_first_digits' => $subscription->current_transaction->card_first_digits,
                        'card_brand' => $subscription->current_transaction->card_brand,
                        'payment_method' => $subscription->current_transaction->payment_method,
                    ]);
                }

                $user->extracts()->create([
                    "user_id" => $user->id,
                    "balance" => $plan->value + $user->balance,
                    "old_balance" => $user->balance,
                    "operation" => "C",
                    "description" => "Início Assinatura - " . $plan->title,
                ]);

                $user->balance += $plan->value;

                $user->save();
                DB::commit();

                Mail::to($user->email)->send(new SubscriptionCreated($sended_mail));

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
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}
