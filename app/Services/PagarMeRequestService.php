<?php

namespace App\Services;

use App\Models\User;
use PagarMe\PagarMe\Client;

class PagarmeRequestService extends BaseRequestService
{
    protected $gateway;
    protected $postback_url;

    private $phones;
    private $address;
    private $billing;
    private $shipping;
    private $items;

    public function __construct()
    {
        $this->gateway = new \PagarMe\Client(env("PAGARME_KEY", 'x'));
        $this->postback_url = env("PAGARME_POSTBACK_URL", env("API_URL", "example.com"));
    }

    // Customer related
    public function setAddress($street, $street_number, $zipcode, $country, $state, $city)
    {
        $this->address = [
            'street' => $street,
            'street_number' => $street_number,
            'zipcode' => $zipcode,
            'country' => $country,
            'state' => $state,
            'city' => $city
        ];

        return $this->gateway;
    }

    public function setFullAddress($street, $street_number, $neighborhood, $zipcode, $country, $state, $city, $complementary = "")
    {
        $this->address = [
            'street' => $street,
            'street_number' => $street_number,
            'zipcode' => $zipcode,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'neighborhood' => $neighborhood,
            'complementary' => $complementary,
        ];

        return $this->gateway;
    }

    public function setPhones(array $phones){
        $this->phones = $phones;
    }

    public function setBilling($name)
    {
        $this->billing = [
            'name' => $name,
            'address' => $this->address
        ];
        return $this;
    }

    public function setShipping($name, $fee)
    {
        $this->shipping = [
            'name' => $name,
            'fee' => $fee,
            'address' => $this->address
        ];
        return $this;
    }

    public function addItem($id, $title, $unit_price, $quantity, $tangible = true)
    {
        $item = [
            "id" => (string) $id,
            "title" => $title,
            "unit_price" => $unit_price,
            "quantity" => $quantity,
            "tangible" => $tangible
        ];

        $this->items[] = $item; 
        return $this;
    }

    public function charge(array $customer, $amount, $payment_method, $card_id = null)
    {
        $data = [
            'customer' => [
                'birthday' => $customer['birthday'],
                'name' => $customer['name'],
                'email' => $customer['email'],
                'external_id' => $customer['external_id'],
                'phone_numbers' => $customer['phone_numbers'],
                'documents' => [
                    [
                        'type' => $customer['documents'][0]['type'],
                        'number' => $customer['documents'][0]['number']
                    ]
                ],
                'type' => $customer['type'],
                'country' => $customer['country']
            ],
            'amount' => $this->shipping['fee'] + $amount,
            'async' => false,
            'postback_url' => route('site.postback'),
            'payment_method' => $payment_method,
            'card_id' => $card_id,
            'billing' => $this->billing,
            'shipping' => $this->shipping,
            'items' => $this->items
        ];

        return $this->post('transactions', $data);
    }

    public function getCustomers()
    {
        return $this->get('customers');
    }

    public function getCustomer($id)
    {
        return $this->gateway->customers()->get([ "id" => $id]);
    }

    public function createCustomer($name, $email, $external_id, array $phone_numbers, array $documents, $type = 'individual', $country = 'br')
    {
        $data = [
            'name' => $name,
            'email' => $email,
            'external_id' => (string) $external_id,
            'phone_numbers' => $phone_numbers,
            'documents' => $documents,
            'type' => $type,
            'country' => $country
        ];

        $result = $this->gateway->customers()->create($data);

        return $result;
    }

    public function getTransaction($id)
    {
        return $this->get(sprintf('%s/%s', 'transactions', $id));
    }

    public function createCreditCard($customer_id, $card_number, $card_expiration_date, $card_holder_name, $card_cvv)
    {
        $data = [
            'customer_id' => $customer_id,
            'card_number' => $card_number,
            'card_expiration_date' => $card_expiration_date,
            'card_expiration_date' => $card_expiration_date,
            'card_holder_name' => $card_holder_name,
            'card_cvv' => $card_cvv
        ];

        return $this->gateway->cards()->create($data);
    }

    public function createSubscription(array $customer, $plan_id, $payment_method, $card_id = null)
    {
        if(!empty($this->address)){
            $customer['address'] = $this->address;
        }
        if(!empty($this->phones)){
            $customer['phone'] = $this->phones;
        }

        // Corrects PagarMe bug that gets a lot of documents
        $customer['documents'] = [collect($customer['documents'])->first()];

        $data = [
            'customer' => $customer,
            'plan_id' => $plan_id,
            'payment_method' => $payment_method,
            'card_id' => $card_id,
            'postback_url' => $this->postback_url
        ];

        return $this->gateway->subscriptions()->create($data);
    }

    public function createPlan($amount, $days, $name, $payment_methods = null, $trial_days = null)
    {
        $data = [
            'amount' => $amount,
            'days' => $days,
            'name' => $name,
            'payment_methods' => !is_null($payment_methods) ? $this->getPaymentMethods($payment_methods) : null,
            'trial_days' => $trial_days
        ];

        return $this->gateway->plans()->create($data);
    }

    public function editPlan($code, $name, $trial_days = null)
    {
        $data = [
            'name' => $name,
            'trial_days' => $trial_days
        ];

        return $this->put(sprintf('%s/%s', 'plans', $code), $data);
    }

    private function getPaymentMethods($type)
    {
        $method = [
            1 => ['boleto'],
            2 => ['credit_card'],
            3 => ['boleto', 'credit_card']
        ];

        return $method[$type];
    }

    public function getBalance()
    {
        return $this->get('balance');
    }

    public function pagarmeToDate($string = "") {
        
    }

    public function dataToPagarme($string = "") {
        $data = strtotime($string);
        $result = date("m", $data) . substr(date("Y", $data), -2);
        return $result;
    }
}