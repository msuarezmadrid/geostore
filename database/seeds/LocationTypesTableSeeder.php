<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class LocationTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('location_types')->insert([
            'name'          => 'Almacén',
            'created_by'	=> 1,
            'updated_by'	=>1,
            'enterprise_id' => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('location_types')->insert([
            'name'          => 'Rack',
            'enterprise_id' => 1,
            'created_by'	=> 1,
            'updated_by'	=>1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('location_types')->insert([
            'name'          => 'Georeferenciado',
            'enterprise_id' => 1,
            'created_by'	=> 1,
            'updated_by'	=>1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

    }
}
