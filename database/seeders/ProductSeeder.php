<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Cria maquina virtual AWS',
                'description' => 'Robô para integração com AWS 100% eficaz',
                'value' => 0.60,
                'simulated_value' => 0.60,
                'scale_quantity' => 1,
                'layout_active' => true,
                'category_id' => 1,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Atualização de localização de veículo',
                'description' => 'Robô para integração com GPS 100% eficaz',
                'value' => 1.10,
                'simulated_value' => 1.10,
                'scale_quantity' => 1,
                'layout_active' => true,
                'category_id' => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cria maquina virtual DigitalOcean',
                'description' => 'Robô para integração com D.O. 100% eficaz',
                'value' => 2.30,
                'simulated_value' => 2.30,
                'scale_quantity' => 1,
                'layout_active' => true,
                'category_id' => 2,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Cria maquina virtual Linux',
                'description' => 'Robô para integração com Hostgator 100% eficaz',
                'value' => 4.64,
                'simulated_value' => 4.64,
                'scale_quantity' => 1,
                'layout_active' => true,
                'category_id' => 3,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
