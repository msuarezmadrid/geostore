<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class SiiTransferTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sii_transfer_types')->insert([
            'id'            => 1,
            'transfer_id'   => 1,
            'name'          => 'Operación constituye venta',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    
        DB::table('sii_transfer_types')->insert([
            'id'            => 2,
            'transfer_id'   => 2,
            'name'          => 'Ventas por efectuar',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 3,
            'transfer_id'   => 3,
            'name'          => 'Consignaciones',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 4,
            'transfer_id'   => 4,
            'name'          => 'Entrega gratuita',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 5,
            'transfer_id'   => 5,
            'name'          => 'Traslados Internos',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 6,
            'transfer_id'   => 6,
            'name'          => 'Otros traslados (no constituye venta)',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 7,
            'transfer_id'   => 7,
            'name'          => 'Guía de devolución',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 8,
            'transfer_id'   => 8,
            'name'          => 'Traslado para exportación (no constituye venta)',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_transfer_types')->insert([
            'id'            => 9,
            'transfer_id'   => 9,
            'name'          => 'Venta para exportación',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);
    }
}
