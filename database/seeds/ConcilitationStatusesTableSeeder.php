<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConcilitationStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('conciliation_statuses')->insert([
            'name'			=> "NO_CONCILIATE",
            'description'   => "No Conciliado",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('conciliation_statuses')->insert([
            'name'			=> "PART_CONCILIATE",
            'description'   => "Parcialmente Conciliado",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);

        DB::table('conciliation_statuses')->insert([
            'name'			=> "CONCILIATE",
            'description'   => "Conciliado",
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }
}
