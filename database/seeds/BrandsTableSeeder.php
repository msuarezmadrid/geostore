<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
        	'id'			=> 1,
            'name'			=> "Cranck Brothers",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 2,
            'name'			=> "SE Bikes",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 3,
            'name'			=> "Shimano Ultegra",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 4,
            'name'			=> "RaceFace",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 5,
            'name'			=> "Cannondale",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 6,
            'name'			=> "SWEAT GUTR",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 7,
            'name'			=> "Blackburn",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 8,
            'name'			=> "Park Tool Co.",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 9,
            'name'			=> "Delta",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
        	'id'			=> 10,
            'name'			=> "Kuat",
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 11,
            'name'          => "Castelli",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 12,
            'name'          => "Kask",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 13,
            'name'          => "ABUS",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 14,
            'name'          => "Light and Motion",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        //ENTERPRISE 3: VREBOLLEDO

        DB::table('brands')->insert([
            'id'            => 16,
            'name'          => "Alternative Apparel",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 17,
            'name'          => "Anvil",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 18,
            'name'          => "CornerStone",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 19,
            'name'          => "District",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 20,
            'name'          => "District Made",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 21,
            'name'          => "Eddie Bauer",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 22,
            'name'          => "Fruit Of The Loom",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 23,
            'name'          => "Gildan",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 24,
            'name'          => "Hanes",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 25,
            'name'          => "Jerzees",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 26,
            'name'          => "New Era",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 27,
            'name'          => "Nike Golf",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 28,
            'name'          => "The North Face",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 29,
            'name'          => "Ogio",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 30,
            'name'          => "Ogio Endurance",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 31,
            'name'          => "Port And Company",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 32,
            'name'          => "Port Authorithy",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 33,
            'name'          => "Red House",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 34,
            'name'          => "Red Kap",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 35,
            'name'          => "Rusell Outdoors",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('brands')->insert([
            'id'            => 36,
            'name'          => "Sport Tek",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }
}
