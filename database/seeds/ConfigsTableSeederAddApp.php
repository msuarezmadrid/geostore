<?php

use App\Config;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeederAddApp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('configs')->insert([
            'param'         => 'APP',
            'value'         => '0',
            'description'   => 'Método de pago con aplicación de pedidos',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
