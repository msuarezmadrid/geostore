<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleBoxDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_box_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seller')->unsigned();
            $table->foreign('seller')->references('id')->on('users');
            $table->integer('sale_box_id')->unsigned();
            $table->foreign('sale_box_id')->references('id')->on('sale_boxes');
            $table->string('transact_id')->nullable();
            $table->integer('type');
            $table->integer('amount')->default(0);
            $table->string('doc_id')->nullable();
            $table->string('observations')->nullable();
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
        Schema::dropIfExists('sale_box_details');

    }
}
