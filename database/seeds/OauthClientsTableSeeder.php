<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OauthClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	//DB::table('users')->truncate();

        /*DB::table('oauth_clients')->insert([
            'id' => 1,
            'name' => 'SIGVET Password Grant Client',
            'secret' => 'ZEoaXSfpCj4rWSDAXYnymV7xPfX8eXpiItcgSlXe',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);*/
    }
}
