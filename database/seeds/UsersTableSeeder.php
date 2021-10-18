<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//DB::table('users')->truncate();

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'system@geostore.cl',
            'password' => bcrypt('secret'),
            'enterprise_id' => 1,
            'role_id' => 1,
            'admin' => 1
        ]);
        
    }
}
