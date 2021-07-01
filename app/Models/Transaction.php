<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = array(
        'pagarme_id',
        'user_id',
        'status',
        'authorization_code',
        'amount',
        'authorized_amount',
        'paid_amount',
        'refunded_amount',
        'installments',
        'cost',
        'subscription_id',
        'postback_url',
        'card_holder_name',
        'card_last_digits',
        'card_first_digits',
        'card_brand',
        'payment_method',
        'boleto_url',
        'boleto_barcode',
        'boleto_expiration_date',
    );
    public function user(){
        return $this->belongsTo(User::class);
    }
}
