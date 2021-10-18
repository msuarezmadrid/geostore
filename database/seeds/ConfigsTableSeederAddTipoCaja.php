<?php

use App\Config;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeederAddTipoCaja extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'param'         => 'TIPO_CAJA',
            'value'         => '0',
            'description'   => 'Tipo de detalle de cierre de caja',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
