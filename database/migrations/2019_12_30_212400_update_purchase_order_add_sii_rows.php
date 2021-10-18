<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePurchaseOrderAddSiiRows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
    

            $table->integer('sii_document_type_id')
                  ->unsigned()
                  ->nullable()
                  ->after('location_id');
            $table->foreign('sii_document_type_id')
                  ->references('id')
                  ->on('sii_document_types');

            $table->integer('sii_document_id')
                  ->unsigned()
                  ->nullable()
                  ->after('location_id');

            $table->integer('sii_transfer_type_id')
                  ->unsigned()
                  ->nullable()
                  ->after('location_id');
            $table->foreign('sii_transfer_type_id')
                  ->references('id')
                  ->on('sii_transfer_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order', function (Blueprint $table) {
            //
        });
    }
}
