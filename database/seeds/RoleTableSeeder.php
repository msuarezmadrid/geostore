<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' 			=> 1,
            'name' 			=> 'Administrador General',
            'type'          => 'admin',
            'enterprise_id'    => 1,
			'created_by'	=> 1,
			'updated_by'	=> 1,
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        DB::table('roles')->insert([
            'id' 			=> 2,
            'name' 			=> 'Vendedor Genérico',
            'type'          => 'gseller',
            'enterprise_id'    => 1,
			'created_by'	=> 1,
			'updated_by'	=> 1,
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

        // DB::table('roles')->insert([
        //     'id' 			=> 3,
        //     'name' 			=> 'Vendedor',
        //     'type'          => 'seller',
        //     'enterprise_id'    => 1,
		// 	'created_by'	=> 1,
		// 	'updated_by'	=> 1,
		// 	'created_at'	=> Carbon::now(),
		// 	'updated_at'	=> Carbon::now()
        // ]);

        DB::table('roles')->insert([
            'id' 			=> 4,
            'name' 			=> 'Cajero',
            'type'          => 'cashier',
            'enterprise_id'    => 1,
			'created_by'	=> 1,
			'updated_by'	=> 1,
			'created_at'	=> Carbon::now(),
			'updated_at'	=> Carbon::now()
        ]);

    }
}
