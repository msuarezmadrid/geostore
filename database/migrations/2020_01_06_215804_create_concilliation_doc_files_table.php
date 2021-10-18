<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcilliationDocFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concilliation_doc_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('concilliation_doc_id')->unsigned();
            $table->foreign('concilliation_doc_id')->references('id')->on('concilliation_docs');
            $table->string('dir');
            $table->string('filename');
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
        Schema::dropIfExists('concilliation_doc_files');
    }
}
