<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
                'user_id' => 3054,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'text' => 'Seu crédito está quase acabando. Não esqueça de fazer uma nova recarga!',
                'is_read' => false,
                'is_notified' => false,
                'user_id' => 3013,
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);
    }
}
