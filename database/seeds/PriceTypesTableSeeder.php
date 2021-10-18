<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PriceTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('price_types')->insert([
            'name'			=> "COMPRA",
            'enterprise_id' => 1,
            'created_by'	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('price_types')->insert([
            'name'          => "VENTA",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

    }
}
