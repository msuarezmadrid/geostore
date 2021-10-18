<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeederAddCreditGenerateTicket extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'param'         => 'CREDIT_GENERATE_TICKET',
            'value'         => '1',
            'description'   => 'Opción para generar una boleta con tipo de pago de crédito',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
