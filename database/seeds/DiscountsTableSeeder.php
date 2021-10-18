<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DiscountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Enterprise TODOSPORT:
        DB::table('discounts')->insert([
            'name'       => 'Sin Descuento',
            'percent'    => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('discounts')->insert([
            'name'       => '10%',
            'percent'    => 10,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
