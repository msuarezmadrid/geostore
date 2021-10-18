<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConciliationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conciliation_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('conciliation_id')->unsigned();
            $table->foreign('conciliation_id')->references('id')->on('conciliations');
            $table->bigInteger('doc_id')->unsigned();
            $table->integer('type');
            $table->bigInteger('amount')->default(0);
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
        Schema::dropIfExists('conciliation_details');
    }
}
