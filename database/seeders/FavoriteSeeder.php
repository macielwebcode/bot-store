<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorites')->insert([
            [
                'user_id' => 3054,
                'product_id' => 1
            ],
            [
                'user_id' => 3054,
                'product_id' => 2
            ],
            [
                'user_id' => 3013,
                'product_id' => 3
            ],
        ]);
    }
}
