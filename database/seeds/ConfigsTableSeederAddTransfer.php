<?php

use App\Config;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeederAddTransfer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('configs')->insert([
            'param'         => 'TRANSFER',
            'value'         => '0',
            'description'   => 'Método de pago por transferencia',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
