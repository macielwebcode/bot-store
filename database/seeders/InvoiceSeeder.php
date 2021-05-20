<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoices')->insert([
            [
                'amount' => 499.00,
                'status' => 'paid',
                'user_id' => 1,
                'plan_id' => 1,
            ],
            [
                'amount' => 899.00,
                'status' => 'paid',
                'user_id' => 2,
                'plan_id' => 3,
            ],
        ]);
    }
}
