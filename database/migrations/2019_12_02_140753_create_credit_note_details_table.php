<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditNoteDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('credit_note_id')->unsigned();
            $table->foreign('credit_note_id')
                  ->references('id')
                  ->on('credit_notes');
            $table->bigInteger('sale_detail_id')
                  ->unsigned();
            $table->foreign('sale_detail_id')
                  ->references('id')
                  ->on('sale_details');
            $table->integer('qty');
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
        Schema::dropIfExists('credit_note_details');
    }
}
