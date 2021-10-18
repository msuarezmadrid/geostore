<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class UnitOfMeasureConversionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 2,
            'uom_to_id' 			=> 3,
            'factor'				=> 0.1,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
        DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 3,
            'uom_to_id' 			=> 2,
            'factor'				=> 10,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
        DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 3,
            'uom_to_id' 			=> 4,
            'factor'				=> 0.01,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 4,
            'uom_to_id' 			=> 3,
            'factor'				=> 100,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 4,
            'uom_to_id' 			=> 2,
            'factor'				=> 1000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 2,
            'uom_to_id' 			=> 4,
            'factor'				=> 0.001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 5,
            'uom_to_id' 			=> 2,
            'factor'				=> 1000000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 2,
            'uom_to_id' 			=> 5,
            'factor'				=> 0.000001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 5,
            'uom_to_id' 			=> 3,
            'factor'				=> 100000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 3,
            'uom_to_id' 			=> 5,
            'factor'				=> 0.00001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 5,
            'uom_to_id' 			=> 4,
            'factor'				=> 1000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 4,
            'uom_to_id' 			=> 5,
            'factor'				=> 0.001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);


         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 6,
            'uom_to_id' 			=> 7,
            'factor'				=> 0.1,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
        DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 7,
            'uom_to_id' 			=> 6,
            'factor'				=> 10,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
        DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 7,
            'uom_to_id' 			=> 8,
            'factor'				=> 0.01,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 8,
            'uom_to_id' 			=> 7,
            'factor'				=> 100,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 8,
            'uom_to_id' 			=> 6,
            'factor'				=> 1000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 6,
            'uom_to_id' 			=> 8,
            'factor'				=> 0.001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 9,
            'uom_to_id' 			=> 6,
            'factor'				=> 1000000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 6,
            'uom_to_id' 			=> 9,
            'factor'				=> 0.000001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 9,
            'uom_to_id' 			=> 7,
            'factor'				=> 100000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 7,
            'uom_to_id' 			=> 9,
            'factor'				=> 0.00001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 9,
            'uom_to_id' 			=> 8,
            'factor'				=> 1000,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 8,
            'uom_to_id' 			=> 9,
            'factor'				=> 0.001,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);

         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 10,
            'uom_to_id' 			=> 11,
            'factor'				=> 0.1,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
         DB::table('unit_of_measure_conversions')->insert([
            'uom_from_id'			=> 11,
            'uom_to_id' 			=> 10,
            'factor'				=> 10,
            'created_at'    		=> Carbon::now(),
            'updated_at'    		=> Carbon::now()
        ]);
        
    }
}
