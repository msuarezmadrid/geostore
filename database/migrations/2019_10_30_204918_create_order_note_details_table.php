<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->double('qty')->nullable();
            $table->integer('price')->nullable();
            $table->string('withdraw');
            $table->bigInteger('order_note_id')->unsigned();
            $table->foreign('order_note_id')->references('id')->on('order_notes');
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
        Schema::dropIfExists('order_note_details');
    }
}
