<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdjustmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjustment_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned(); 
            $table->foreign('item_id')->references('id')->on('items');
            $table->integer('adjustment_id')->unsigned()->nullable(); 
            $table->foreign('adjustment_id')->references('id')->on('adjustments');
            $table->float('quantity')->default(0);
            $table->integer('unit_of_measure_id')->unsigned()->nullable(); 
            $table->foreign('unit_of_measure_id')->references('id')->on('unit_of_measures');
            $table->integer('created_by')->unsigned(); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->nullable(); 
            $table->foreign('updated_by')->references('id')->on('users');
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
        Schema::dropIfExists('adjustment_items');
    }
}
