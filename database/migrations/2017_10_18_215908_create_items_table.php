<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('custom_sku')->nullable();
            $table->string('manufacturer_sku')->nullable();
            $table->string('ean')->nullable();
            $table->string('upc')->nullable();
            $table->string('name');
            $table->integer('category_id')->unsigned()->nullable(); 
            $table->foreign('category_id')->references('id')->on('categories');

            $table->integer('unit_of_measure_id')->unsigned()->nullable(); 
            $table->foreign('unit_of_measure_id')->references('id')->on('unit_of_measures');


            $table->float('reorder_point')->default(0);
            $table->float('order_up_to')->default(0);

            $table->integer('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->longText('description')->nullable();
            $table->boolean('is_bom')->default(false);
            $table->integer('enterprise_id')->unsigned();
            $table->foreign('enterprise_id')->references('id')->on('enterprises');
            $table->integer('created_by')->unsigned(); 
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('updated_by')->unsigned()->nullable(); 
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
