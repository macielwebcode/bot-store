<?php

namespace Database\Seeders;

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
                'scale_quantity' => 1,
                'layout_active' => true,
            ],
            [
                'name' => 'Atualização de localização de veículo',
                'description' => 'Robô para integração com GPS 100% eficaz',
                'value' => 1.10,
                'scale_quantity' => 1,
                'layout_active' => true,
            ],
            [
                'name' => 'Cria maquina virtual DigitalOcean',
                'description' => 'Robô para integração com D.O. 100% eficaz',
                'value' => 2.30,
                'scale_quantity' => 1,
                'layout_active' => true,
            ],
            [
                'name' => 'Cria maquina virtual Linux',
                'description' => 'Robô para integração com Hostgator 100% eficaz',
                'value' => 4.64,
                'scale_quantity' => 1,
                'layout_active' => true,
            ],
        ]);
    }
}
