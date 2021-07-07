<?php

namespace App\Services;

use App\Helpers\ResponseHelper;
use App\Models\Extract;
use Illuminate\Support\Facades\Log;

class BalanceManager {


    public static function addBalance($user, $amount, $description){

        $new_balance = ($amount + $user->balance);

        $registry = [
            'user_id' => $user->id,
            'old_balance' => $user->balance,
            'balance' => $new_balance,
            'operation' => 'C',
            'description' => $description
        ];
        $user->extracts()->create($registry);

        $user->balance = $new_balance;
        $user->save();
        ResponseHelper::log($registry, __("Inserindo crédito ao cliente {$user->id} -> $amount"));
    }

    public static function removeBalance($user, $amount, $description){

        $new_balance = ($amount - $user->balance);

        $registry = [
            'user_id' => $user->id,
            'old_balance' => $user->balance,
            'balance' => $new_balance,
            'operation' => 'D',
            'description' => $description
        ];
        $user->extracts()->create($registry);

        $user->balance = $new_balance;
        $user->save();
        ResponseHelper::log($registry, __("Inserindo crédito ao cliente {$user->id} -> $amount"));
    }
}