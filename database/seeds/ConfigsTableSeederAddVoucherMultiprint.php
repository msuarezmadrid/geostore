<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeederAddVoucherMultiprint extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'param'         => 'VOUCHER_MULTIPRINT',
            'value'         => 'DEFAULT',
            'description'   => 'Headers de voucher para multi-impresión, DEFAULT toma el ya existente, Delimitado por ;',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
