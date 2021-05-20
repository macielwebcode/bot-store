<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifications')->insert([
            [
                'text' => 'Novo bot disponível - conheça o Bot YankW',
                'is_read' => false,
                'is_notified' => false,
                'user_id' => 1
            ],
            [
                'text' => 'Seu crédito está quase acabando. Não esqueça de fazer uma nova recarga!',
                'is_read' => false,
                'is_notified' => false,
                'user_id' => 1
            ],
        ]);
    }
}
