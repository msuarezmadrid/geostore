<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->date('date');
            $table->integer('client_id')
                  ->unsigned()
                  ->nullable();
            $table->foreign('client_id')
                  ->references('id')
                  ->on('clients');
            $table->integer('seller_id')
                   ->unsigned()
                   ->nullable();
            $table->foreign('seller_id')
                  ->references('id')
                  ->on('sellers');
            $table->integer('type');
            $table->string('payment_method');
            $table->integer('conciliation_status_id')->unsigned();
            $table->foreign('conciliation_status_id')
                  ->references('id')
                  ->on('conciliation_statuses');
            $table->integer('net')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('sales');
    }
}
