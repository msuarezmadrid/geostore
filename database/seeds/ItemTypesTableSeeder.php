<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ItemTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_types')->insert([
            'name' 			=> 'Simple',
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
        /*
        DB::table('item_types')->insert([
            'name' 			=> 'Variable',
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
        */
        DB::table('item_types')->insert([
            'name' 			=> 'Compuesto',
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);
    }
}
