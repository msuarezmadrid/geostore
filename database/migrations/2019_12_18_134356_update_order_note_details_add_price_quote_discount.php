<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOrderNoteDetailsAddPriceQuoteDiscount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_note_details', function (Blueprint $table) {
            //

            // $table->float('withdraw')
            // ->nullable()
            // ->change();

            DB::statement('ALTER TABLE `order_note_details` MODIFY `withdraw` VARCHAR(100) NULL;');

            $table->float('discount_percent')
            ->after('name')
            ->nullable(); 

            $table->string('price_quote_description')
            ->after('name')
            ->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_note_details', function (Blueprint $table) {
            //
        });
    }
}
