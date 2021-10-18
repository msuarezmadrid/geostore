<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attributes')->insert([
        	'id'			=> 1,
            'name'			=> "TALLA",
            'type'			=> 'text',
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
        	'id'			=> 2,
            'name'			=> "COLOR",
            'type'			=> 'text',
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
        	'id'			=> 3,
            'name'			=> "FECHA DE ELABORACIÓN",
            'type'			=> 'date',
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
        	'id'			=> 4,
            'name'			=> "FECHA DE VENCIMIENTO",
            'type'			=> 'date',
            'enterprise_id' => 1,
            'created_by' 	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        /*ENTERPRISE 3: VREBOLLEDO*/
        DB::table('attributes')->insert([
            'id'            => 5,
            'name'          => "TALLA",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
            'id'            => 6,
            'name'          => "COLOR",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
            'id'            => 7,
            'name'          => "FECHA DE ELABORACIÓN",
            'type'          => 'date',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
            'id'            => 8,
            'name'          => "FECHA DE VENCIMIENTO",
            'type'          => 'date',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('attributes')->insert([
            'name'          => "TALLA",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
            'name'          => "COLOR",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        // Enterprise 5

        DB::table('attributes')->insert([
            'name'          => "TALLA",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('attributes')->insert([
            'name'          => "COLOR",
            'type'          => 'text',
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        
    }
}
