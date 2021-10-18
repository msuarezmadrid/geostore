<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementsHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_historical', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned(); 
            
            $table->integer('location_id')->unsigned(); 
            $table->foreign('location_id')->references('id')->on('locations');
            $table->integer('movement_status_id')->unsigned(); 
            $table->foreign('movement_status_id')->references('id')->on('movement_statuses');
            $table->string('movement_type');
            $table->integer('enterprise_id')->unsigned();
            $table->foreign('enterprise_id')->references('id')->on('enterprises');
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
        Schema::dropIfExists('movement_historical');
    }
}
