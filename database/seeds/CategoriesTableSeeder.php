<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$cats = ["Semiconductors", "Passives", "Electromechanical", "Power - Circuit Protection", "Connectors - Interconnect", 
                "Cables - Wires", "Tools", "Enclosures . Hardware - Office", "Others"];
        $subcats = [ 
            [ "id" => 1 , "name" => "Development Boards - Kits"],
            [ "id" => 1 , "name" => "Programmers"],
            [ "id" => 1 , "name" => "Discrete"],
            [ "id" => 1 , "name" => "Embedded Computers"],
            [ "id" => 1 , "name" => "Integrated Circuits (ICs)"],
            [ "id" => 1 , "name" => "Isolators"],
            [ "id" => 1 , "name" => "LED/Optoelectronics"],
            [ "id" => 1 , "name" => "RF - Wireless"],
            [ "id" => 1 , "name" => "Sensors - Transducers"],
            [ "id" => 2 , "name" => "Capacitors"],
            [ "id" => 2 , "name" => "Crystals - Oscillators"],
            [ "id" => 2 , "name" => "Filters"],
            [ "id" => 2 , "name" => "Inductors - Coils - Chokes"],
            [ "id" => 2 , "name" => "Potentiometers - Variable Resistors"],
            [ "id" => 2 , "name" => "Resistors"],
            [ "id" => 2 , "name" => "Thermal Managemente"],
            [ "id" => 3 , "name" => "Audio"],
            [ "id" => 3 , "name" => "Fans"],
            [ "id" => 3 , "name" => "Industrial Controls"],
            [ "id" => 3 , "name" => "Motors - Solenoids - Driver"],
            [ "id" => 3 , "name" => "Relays"],
            [ "id" => 3 , "name" => "Switches"],
            [ "id" => 4 , "name" => "Battery Products"],
            [ "id" => 4 , "name" => "Circuit Protection"],
            [ "id" => 4 , "name" => "Line Protection"],
            [ "id" => 4 , "name" => "Power Supplies"],
            [ "id" => 4 , "name" => "Transformers"]
            ];

            foreach ($cats as $key => $cat) {
                DB::table('categories')->insert([
                    'name'          => $cat,
                    'category_id'   => null,
                    'enterprise_id' => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
            }

            foreach ($subcats as $key => $subcat) {
                DB::table('categories')->insert([
                    'name'          => $subcat['name'],
                    'category_id'   => $subcat['id'],
                    'enterprise_id' => 1,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
            }

        //ZAPATOS 
        $cats = ["SPORTS", "OUTDOOR", "CASUAL", "SKATEBOARDING"];
        $subcats = [ 
            [ "id" => 37 , "name" => "Hombres"],
            [ "id" => 37 , "name" => "Mujeres"],
            [ "id" => 38 , "name" => "Hombres"],
            [ "id" => 38 , "name" => "Mujeres"],
            [ "id" => 39 , "name" => "Hombres"],
            [ "id" => 39 , "name" => "Mujeres"],
            [ "id" => 40 , "name" => "Hombres"],
            [ "id" => 40 , "name" => "Mujeres"],
            ];

            foreach ($cats as $key => $cat) {
                DB::table('categories')->insert([
                    'name'          => $cat,
                    'category_id'   => null,
                    'enterprise_id' => 4,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
            }

            foreach ($subcats as $key => $subcat) {
                DB::table('categories')->insert([
                    'name'          => $subcat['name'],
                    'category_id'   => $subcat['id'],
                    'enterprise_id' => 4,
                    'created_by'    => 1,
                    'updated_by'    => 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now()
                ]);
            }


        /*
        DB::table('categories')->insert([
        	'id'			=> 1,
            'name'			=> "ACCESORIES",
            'category_id'	=> null,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 2,
            'name'			=> "CAMERAS",
            'category_id'	=> 1,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 3,
            'name'			=> "GLASSES",
            'category_id'	=> 1,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 4,
            'name'			=> "LIGHTS",
            'category_id'	=> 1,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 5,
            'name'			=> "LOCKS",
            'category_id'	=> 1,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 6,
            'name'			=> "WATCHES",
            'category_id'	=> 1,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('categories')->insert([
        	'id'			=> 7,
            'name'			=> "APPAREL",
            'category_id'	=> null,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 8,
            'name'			=> "MOUNTAIN",
            'category_id'	=> 7,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 9,
            'name'			=> "CASUAL",
            'category_id'	=> 8,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 10,
            'name'			=> "HATS",
            'category_id'	=> 9,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 11,
            'name'			=> "OTHERS",
            'category_id'	=> 9,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 12,
            'name'			=> "SHORTS",
            'category_id'	=> 9,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 13,
            'name'			=> "SOCKS",
            'category_id'	=> 9,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 14,
            'name'			=> "T-SHIRTS",
            'category_id'	=> 9,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 15,
            'name'			=> "SPORT",
            'category_id'	=> 7,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 16,
            'name'			=> "BIBS",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 17,
            'name'			=> "GLOBES",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 18,
            'name'			=> "JERSEYS",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 19,
            'name'			=> "OTHERS",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 20,
            'name'			=> "SHORTS",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 21,
            'name'			=> "SOCKS",
            'category_id'	=> 15,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
         DB::table('categories')->insert([
        	'id'			=> 22,
            'name'			=> "TRIATHLON",
            'category_id'	=> 7,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 23,
            'name'			=> "HELMETS",
            'category_id'	=> null,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
        	'id'			=> 24,
            'name'			=> "ROAD",
            'category_id'	=> 23,
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        //Enterprise 3: rebolledo
        DB::table('categories')->insert([
            'id'            => 25,
            'name'          => "ACCESORIES",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 26,
            'name'          => "APRONS",
            'category_id'   => 25,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 27,
            'name'          => "BAGS",
            'category_id'   => 25,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 28,
            'name'          => "ROBES & TOWELS",
            'category_id'   => 25,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 29,
            'name'          => "SCARVES & GLOVES",
            'category_id'   => 25,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 30,
            'name'          => "OTHER",
            'category_id'   => 25,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 31,
            'name'          => "ACTIVEWEAR",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 32,
            'name'          => "ATHLETIC & WARM-UPS",
            'category_id'   => 31,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 33,
            'name'          => "JERSEYS",
            'category_id'   => 31,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 34,
            'name'          => "LADIES",
            'category_id'   => 31,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 35,
            'name'          => "PANTS & SHORTS",
            'category_id'   => 31,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 36,
            'name'          => "PERFORMANCE",
            'category_id'   => 31,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 37,
            'name'          => "CAPS",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 38,
            'name'          => "CAMOUFLAGE",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 39,
            'name'          => "FASHION",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 40,
            'name'          => "FLEECE & BEANIES",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('categories')->insert([
            'id'            => 41,
            'name'          => "LADIES",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 42,
            'name'          => "PERFORMANCE",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 43,
            'name'          => "SAFETY",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 44,
            'name'          => "VISORS",
            'category_id'   => 37,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 45,
            'name'          => "LADIES",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 46,
            'name'          => "ACTIVEWEAR",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 47,
            'name'          => "DRESSES",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 48,
            'name'          => "FASHION",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 49,
            'name'          => "OUTERWEAR",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 50,
            'name'          => "POLOS & KNITS",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 51,
            'name'          => "SWEATSHIRTS & FLEECE",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 52,
            'name'          => "T-SHIRTS",
            'category_id'   => 45,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 53,
            'name'          => "OUTERWEAR",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 54,
            'name'          => "ATHLETIC & WARM-UPS",
            'category_id'   => 53,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 55,
            'name'          => "CORPORATE JACKETS",
            'category_id'   => 53,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 56,
            'name'          => "INSULATED JACKETS",
            'category_id'   => 53,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 57,
            'name'          => "SOFT SHELLS",
            'category_id'   => 53,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 58,
            'name'          => "WORK JACKETS",
            'category_id'   => 53,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 59,
            'name'          => "POLOS & KNITS",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 60,
            'name'          => "BASIC KNITS",
            'category_id'   => 59,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 61,
            'name'          => "COTTON",
            'category_id'   => 59,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 62,
            'name'          => "SWEATERS",
            'category_id'   => 59,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 63,
            'name'          => "YOUTH",
            'category_id'   => 59,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 64,
            'name'          => "SWEATSHIRTS",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 65,
            'name'          => "CREWNECKS",
            'category_id'   => 64,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 66,
            'name'          => "FLEECE",
            'category_id'   => 64,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 67,
            'name'          => "FULL ZIP",
            'category_id'   => 64,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 68,
            'name'          => "1/4 & 1/2 ZIP",
            'category_id'   => 64,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 69,
            'name'          => "HOODIE",
            'category_id'   => 64,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('categories')->insert([
            'id'            => 70,
            'name'          => "T-SHIRTS",
            'category_id'   => null,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('categories')->insert([
            'id'            => 71,
            'name'          => "COTTON",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 72,
            'name'          => "50-50 BLEND",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 73,
            'name'          => "FASHION",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 74,
            'name'          => "PERFORMANCE",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 75,
            'name'          => "WORKWEAR",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('categories')->insert([
            'id'            => 76,
            'name'          => "YOUTH",
            'category_id'   => 70,
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);*/
    }   
}
