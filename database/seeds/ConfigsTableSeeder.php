<?php

use App\Config;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('configs')->insert([
            'param'         => 'ROUNDING',
            'value'         => 'DEFAULT',
            'description'   => 'Método de redondeo mediante pago por efectivo',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'CASH',
            'value'         => '1',
            'description'   => 'Método de pago en efectivo',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'CHEQUE',
            'value'         => '1',
            'description'   => 'Método de pago con cheque',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'CARD',
            'value'         => '1',
            'description'   => 'Método de pago con tarjeta',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'INTERN',
            'value'         => '0',
            'description'   => 'Método de pago con crédito interno',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'MIX',
            'value'         => '0',
            'description'   => 'Método de pago mixto',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'LIGHT_THEME',
            'value'         => '0',
            'description'   => 'Tema en modulo de preventa',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'DIRECT_PRINT',
            'value'         => '0',
            'description'   => 'Impresión directa en modulo preventa',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'PRESALE_POS_ITEM_QUANTITY',
            'value'         => '10',
            'description'   => 'Cantidad de productos mostrados en modulo venta',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('configs')->insert([
            'param'         => 'CEDIBLE',
            'value'         => '1',
            'description'   => 'Copia cedible de factura',
            'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
