<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'balance',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptions(){
        return $this->hasMany(Subscription::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function extracts(){
        return $this->hasMany(Extract::class);
    }

    // public function plan() {
    //     return $this->invoices()->where("status", "paid");
    //     Plan::class, Invoice::class);
    // }

    public function favoriteProducts(){
        return $this->belongsToMany(Product::class, 'favorites')->as('favorite');
    }
    
    public function activeProducts(){
        return $this->belongsToMany(Product::class, 'active_products')->as('active');
    }

    public function usercards(){
        return $this->hasMany(UserCard::class);
    }

    public function balance(){
        $last_balance = 0;
        $last_balance = collect($this->extracts())->last(null, json_decode(json_encode([ 'balance' => 0]), 1))->balance;

        return $last_balance;
    }

    // public function balance($amount, $operation = null, $description = ""){
    //     $allowed_operations = array( "C", "D" );

    //     $ret = false;
    //     $balance = $this->balance();

    //     if($amount > 0 && collect($allowed_operations)->has($operation)) {

    //     }
    // }
}
