<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            [
                'title' => 'PayPal',
                'description' => 'Utilize o cartão e receba os créditos na hora',
                'settings' => '',
                'active' => true,
                'tax_percent' => 0,
                'tax_amount' => 1.50,
            ],
        ]);
    }
}
