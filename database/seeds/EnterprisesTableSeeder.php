<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EnterprisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('enterprises')->insert([
            'id'            => 1,
            'name'           => env('APP_NAME', 'EMPRESA'),
            'updated_by'    => 1,
            'created_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }
}
