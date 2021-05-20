<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table("users")->insert([
            [
                'id' => 3013,
                'name' => "Marcela Maciel",
                'email' => 'maciel@gmail.com',
                'password' => '123123123',
                'cpf' => '596.772.910-09',
                'balance' => 0,
                'status' => 1
            ],
            [
                'id' => 3054,
                'name' => "Vitor Pereira",
                'email' => 'vini.vptds@gmail.com',
                'password' => '11223344',
                'cpf' => '417.105.558-07',
                'balance' => 0,
                'status' => 1
            ]
        ]);
        // $this->call(DatabaseSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(FavoriteSeeder::class);
    }
}
