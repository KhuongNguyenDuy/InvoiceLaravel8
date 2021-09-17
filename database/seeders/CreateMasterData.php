<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateMasterData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Le Thanh Nghi',
            'email' => 'nghilt@vaixgroup.com',
            'password' => '$2y$10$kw0HFyoO89lzGCCM1qLODOm38aswNX.r6yKT1kwMY/CiKgw8daCP2'
        ]);

        DB::table('users')->insert([
            'name' => 'Nguyen Viet Hung',
            'email' => 'nvh@vaixgroup.com',
            'password' => '$2y$10$kw0HFyoO89lzGCCM1qLODOm38aswNX.r6yKT1kwMY/CiKgw8daCP2'
        ]);

        DB::table('customers')->insert([
            'name' => '株式会社システムズナカシマ　東京支店',
            'abbreviate' => 'SNC',
            'address' => '東京都千代田区岩本町2-8-8ユニゾ岩本町2丁目ビル2F',
            'phone' => '(+813)5821-9761',
            'fax' => '(+813)5821-9762',
            'director_name' => '中島　義雄',
            'establish_date' => '1985/04/01',
            'capital' => '100000000',
            'employee_num' => 130
        ]);

        DB::table('customers')->insert([
            'name' => '株式会社Andtech',
            'abbreviate' => 'AT',
            'address' => '神奈川県川崎市多摩区登戸1936-104',
            'phone' => '044-455-5720',
        ]);

        DB::table('projects')->insert([
            'name' => 'WebExpo開発支援',
            'customer_id' => 1
        ]);

        DB::table('items')->insert([
            'name' => '8月分の開発費用',
            'price' => 300000,
            'project_id' => 1
        ]);
    }
}
