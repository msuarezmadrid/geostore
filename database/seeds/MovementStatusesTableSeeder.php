<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class MovementStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('movement_statuses')->insert([
            'name'          => 'BORRADOR',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('movement_statuses')->insert([
            'name'          => 'PENDIENTE',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
        DB::table('movement_statuses')->insert([
            'name'          => 'APROBADO',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }
}
