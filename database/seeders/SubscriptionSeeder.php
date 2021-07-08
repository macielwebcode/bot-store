<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscriptions')->insert([
            [
                'status'     => 'paid',
                'user_id'    => 3054,
                'plan_id'    => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'status'     => 'paid',
                'user_id'    => 3013,
                'plan_id'    => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
