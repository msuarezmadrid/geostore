<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemTypeColumnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('item_type_id')->unsigned(); 
            $table->foreign('item_type_id')->references('id')->on('item_types')->nullable();
            $table->integer('item_id')->unsigned()->nullable(); 
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign('items_item_id_foreign');
            $table->dropForeign('items_item_type_id_foreign');
            $table->dropColumn('item_id');
            $table->dropColumn('item_type_id');
            
        });
    }
}
