<?php

namespace App\Http\Handlers;
use App\Models\Extract;
use Illuminate\Support\Facades\DB;

trait BalanceHandler {

    private function _add($user, $amount, $description){

        DB::beginTransaction();

        if($user){

            $extract_data = array(
                "old_balance" => $user->balance,
                "balance" => $amount + $user->balance,
                "operation" => 'C',
                "description" => $description,
            );

            Extract::create($extract_data);

            $user->balance += $amount;
            $user->save();

            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }

    private function _remove($user, $amount, $description){

        DB::beginTransaction();

        if($user){

            $extract_data = array(
                "old_balance" => $user->balance,
                "balance" => $amount - $user->balance,
                "operation" => 'D',
                "description" => $description,
                "user_id" => $user->id
            );

            Extract::create($extract_data);

            $user->balance -= $amount;
            $user->save();

            DB::commit();
            return true;
        }
        DB::rollback();
        return false;
    }
}
