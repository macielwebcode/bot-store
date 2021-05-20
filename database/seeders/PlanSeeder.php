<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            [
                'title' => 'Light',
                'description' => 'Para conhecer nossas automações',
                'charge_period' => '30',
                'value' => 499.90,
                'max_usage' => 5000,
                'max_bots' => 5,
                'balance_saving' => false,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'title' => 'Pro',
                'description' => 'Para sair na frente dos concorrentes',
                'charge_period' => '30',
                'value' => 1399.90,
                'max_usage' => 20000,
                'max_bots' => 20,
                'balance_saving' => true,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'title' => 'Enterprise',
                'description' => 'Para alcançar as estrelas',
                'charge_period' => '30',
                'value' => 2699.90,
                'max_usage' => -1,
                'max_bots' => -1,
                'balance_saving' => true,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
