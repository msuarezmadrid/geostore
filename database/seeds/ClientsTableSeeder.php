<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->insert([
            'name'          => "GENERIC",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'rut'           => '66666666',
            'rut_dv'        => '6',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        /*DB::table('clients')->insert([
            'name'			=> "ATM Trade International",
            'enterprise_id' => 1,
            'created_by'	=> 1,
            'updated_by'	=> 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Fred",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Susan",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Ton",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Rahul",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Bradley",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Ariel",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Cantarez",
            'enterprise_id' => 1,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('clients')->insert([
            'name'          => "Fred",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Susan",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Ton",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Rahul",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Bradley",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Ariel",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('clients')->insert([
            'name'          => "Cantarez",
            'enterprise_id' => 3,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('clients')->insert([
            'name'          => "Publico General",
            'enterprise_id' => 4,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('clients')->insert([
            'name'          => "Cliente 1",
            'enterprise_id' => 4,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('clients')->insert([
            'name'          => "Cliente 2",
            'enterprise_id' => 4,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('clients')->insert([
            'name'          => "Cliente 3",
            'enterprise_id' => 4,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);


        DB::table('clients')->insert([
            'name'          => "Cliente",
            'enterprise_id' => 5,
            'created_by'    => 1,
            'updated_by'    => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);*/
    }
}
