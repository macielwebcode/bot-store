<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'id'            => 1,
                'title'         => 'Backoffice e TI',
                'description'   => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'            => 2,
                'title'         => 'Contas a Pagar',
                'description'   => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'            => 3,
                'title'         => 'Juridico',
                'description'   => 'Lorem ipsum toder clareted keep the history',
                'layout_active' => true,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
