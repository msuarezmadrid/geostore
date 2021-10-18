<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class SiiDocumentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('sii_document_types')->insert([
            'id'            => 1,
            'document_id'   => 33,
            'description'          => 'Factura Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 2,
            'document_id'   => 34,
            'description'          => 'Factura Afecta o no Exenta Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 3,
            'document_id'   => 39,
            'description'          => 'Boleta Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 4,
            'document_id'   => 41,
            'description'          => 'Boleta Exenta Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 5,
            'document_id'   => 46,
            'description'          => 'Factura de Compra Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 6,
            'document_id'   => 56,
            'description'          => 'Nota de Débito Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 7,
            'document_id'   => 61,
            'description'          => 'Nota de Crédito Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 8,
            'document_id'   => 50,
            'description'          => 'Guía de Despacho',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 9,
            'document_id'   => 52,
            'description'          => 'Guía de Despacho Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 10,
            'document_id'   => 30,
            'description'          => 'Factura',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 11,
            'document_id'   => 32,
            'description'          => 'Factura de Venta Bienes y Servicios no afectos o exentos de IVA',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 12,
            'document_id'   => 35,
            'description'          => 'Boleta',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 13,
            'document_id'   => 38,
            'description'          => 'Boleta Exenta',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 14,
            'document_id'   => 45,
            'description'          => 'Factura de Compra',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 15,
            'document_id'   => 55,
            'description'          => 'Nota de débito',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 16,
            'document_id'   => 60,
            'description'          => 'Nota de crédito',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 17,
            'document_id'   => 103,
            'description'          => 'Liquidación',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 18,
            'document_id'   => 40,
            'description'          => 'Liquidación Factura',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 19,
            'document_id'   => 43,
            'description'          => 'Liquidación Factura Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 20,
            'document_id'   => 110,
            'description'          => 'Factura de Exportación Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 21,
            'document_id'   => 111,
            'description'          => 'Nota de Débito de Exportación Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 22,
            'document_id'   => 112,
            'description'          => 'Nota de Crédito de Exportación Electrónica',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 23,
            'document_id'   => 801,
            'description'          => 'Orden de Compra',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

        DB::table('sii_document_types')->insert([
            'id'            => 24,
            'document_id'   => 802,
            'description'          => 'Nota de Pedido',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ]);

    }
}
