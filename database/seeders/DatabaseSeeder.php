<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'password' => Hash::make('123456'),
                'cpf' => '596.772.910-09',
                'phone' => '18998109428',
                'balance' => 0,
                'status' => 1,
                'is_admin' => 1,

                'cep' => '19062270',
                'street' => 'Rua Jose Junior',
                'district' => 'Jardim Ira',
                'number' => '32',
                'complement' => '',
                'city' => "Rancharia",
                'state' => "SP",
                'country' => 'BR',
            ],
            [
                'id' => 3054,
                'name' => "Vitor Pereira",
                'email' => 'vini.vptds@gmail.com',
                'password' => Hash::make('11223344'),
                'cpf' => '417.105.558-07',
                'phone' => '18998109428',
                'balance' => 0,
                'status' => 1,
                'is_admin' => 0,

                'cep' => '19780000',
                'street' => 'Rua Maria Antonia',
                'district' => 'Vila Rosa',
                'number' => '1198',
                'complement' => '',
                'city' => "Quatá",
                'state' => "SP",
                'country' => 'BR',

            ]
        ]);
        // $this->call(DatabaseSeeder::class);
        $this->call(PaymentMethodSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(SubscriptionSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(FavoriteSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
