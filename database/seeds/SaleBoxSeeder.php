<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleBoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branch_offices')->insert([
            'name'       => 'Sucursal 1',
            'code'       => '001',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'enterprise_id' => 1
        ]);

        DB::table('sale_boxes')->insert([
            'name'       => 'Caja 1',
            'status'    => 0,
            'branch_office_id' => 1,
            'location_id'   => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);


    }
}
