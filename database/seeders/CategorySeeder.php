<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'title' => 'Backoffice e TI',
                'description' => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true
            ],
            [
                'title' => 'Contas a Pagar',
                'description' => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true
            ],
            [
                'title' => 'Juridico',
                'description' => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true
            ],
        ]);
    }
}
