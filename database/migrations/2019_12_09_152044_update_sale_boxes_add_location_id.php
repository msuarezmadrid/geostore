<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSaleBoxesAddLocationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_boxes', function (Blueprint $table) {
            $table->integer('location_id')
                  ->unsigned()
                  ->nullable()
                  ->after('seller');
            $table->foreign('location_id')
                  ->references('id')
                  ->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_boxes', function (Blueprint $table) {
            //
        });
    }
}
