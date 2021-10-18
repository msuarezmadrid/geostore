<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        DB::table('locations')->insert([
            'name'          => 'Almacén Principal',
            'description'   => 'Bodega Principal',
            //CODIGO DE ALMACÉN REVERTIDO DE ALM-01 A TS-01 COMO QUICKFIX DEBIDO A USO COMO VALORES FIJOS EN PRESALE
            'code'          => 'TS-01',
            'address'       => "Valdivia",
            'location_type_id' => 1,
            'created_by'    => 1,
            'updated_by'    =>1,
            'enterprise_id' => 1,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]);
    }
}
