<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sale_id')->unsigned();
            $table->foreign('sale_id')
                  ->references('id')
                  ->on('sales');
            $table->integer('item_id')->unsigned()->nullable();
            $table->foreign('item_id')
                  ->references('id')
                  ->on('items');
            $table->double('price');
            $table->double('qty');
            $table->double('discount_percent');
            $table->double('conciliated'); 
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
        Schema::dropIfExists('sale_details');
    }
}
