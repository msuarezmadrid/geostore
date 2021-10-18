<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateConcilliationDocsAddNumberDoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concilliation_docs', function (Blueprint $table) {
            $table->string('doc_number')
            ->after('payment_method')
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
        Schema::table('concilliation_docs', function (Blueprint $table) {
            //
        });
    }
}
