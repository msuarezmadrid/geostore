<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitOfMeasureConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_of_measure_conversions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uom_from_id')->unsigned();
            $table->foreign('uom_from_id')->references('id')->on('unit_of_measures');
            $table->integer('uom_to_id')->unsigned();
            $table->foreign('uom_to_id')->references('id')->on('unit_of_measures');
            $table->double('factor');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_of_measure_conversions');
    }
}
