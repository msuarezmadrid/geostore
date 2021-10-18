<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folio');
            $table->bigInteger('sale_id')->unsigned();
            $table->foreign('sale_id')
                  ->references('id')
                  ->on('sales');
            $table->integer('type')->unsigned();
            $table->integer('credit_type')->unsigned();
            $table->integer('net')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total')->default(0);
            $table->string('observations');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')
                  ->references('id')
                  ->on('users');
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
        Schema::dropIfExists('credit_notes');
    }
}
