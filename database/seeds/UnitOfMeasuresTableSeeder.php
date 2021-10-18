<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UnitOfMeasuresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('unit_of_measures')->insert([
            'abbr'			=> "u",
            'name' 			=> "Unidad",
            'plural'		=> "Unidades",
            'system' 		=> "",
            'measure' 		=> "quantity",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "mm",
            'name' 			=> "Milimetro",
            'plural'		=> "Milimetros",
            'system' 		=> "metric",
            'measure' 		=> "length",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "cm",
            'name' 			=> "Centimetro",
            'plural'		=> "Centimetros",
            'system' 		=> "metric",
            'measure' 		=> "length",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "m",
            'name' 			=> "Metro",
            'plural'		=> "Metros",
            'system' 		=> "metric",
            'measure' 		=> "length",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "km",
            'name' 			=> "Kilometro",
            'plural'		=> "Kilometros",
            'system' 		=> "metric",
            'measure' 		=> "length",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('unit_of_measures')->insert([
            'abbr'			=> "mm2",
            'name' 			=> "Milimetro Cuadrado",
            'plural'		=> "Milimetros Cuadrados",
            'system' 		=> "metric",
            'measure' 		=> "area",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "cm2",
            'name' 			=> "Centimetro Cuadrado",
            'plural'		=> "Centimetros Cuadrados",
            'system' 		=> "metric",
            'measure' 		=> "area",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "m2",
            'name' 			=> "Metro Cuadrado",
            'plural'		=> "Metros Cuadrados",
            'system' 		=> "metric",
            'measure' 		=> "area",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "km2",
            'name' 			=> "Kilometro Cuadrado",
            'plural'		=> "Kilometros Cuadrados",
            'system' 		=> "metric",
            'measure' 		=> "area",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('unit_of_measures')->insert([
            'abbr'			=> "mL",
            'name' 			=> "Mililitro Cúbico",
            'plural'		=> "Mililitros Cúbicos",
            'system' 		=> "metric",
            'measure' 		=> "volume",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'          => "cc",
            'name'          => "Centimetro Cúbico",
            'plural'        => "Centimetros Cúbicos",
            'system'        => "metric",
            'measure'       => "volume",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "L",
            'name' 			=> "Litro",
            'plural'		=> "Litros",
            'system' 		=> "metric",
            'measure' 		=> "volume",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "kL",
            'name' 			=> "Kilolitro",
            'plural'		=> "Kilolitros",
            'system' 		=> "metric",
            'measure' 		=> "volume",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('unit_of_measures')->insert([
            'abbr'			=> "mg",
            'name' 			=> "Miligramo",
            'plural'		=> "Miligramos",
            'system' 		=> "metric",
            'measure' 		=> "weight",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "g",
            'name' 			=> "Gramo",
            'plural'		=> "Gramos",
            'system' 		=> "metric",
            'measure' 		=> "weight",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('unit_of_measures')->insert([
            'abbr'			=> "kg",
            'name' 			=> "Kilogramo",
            'plural'		=> "Kilogramos",
            'system' 		=> "metric",
            'measure' 		=> "weight",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        

    }
}
