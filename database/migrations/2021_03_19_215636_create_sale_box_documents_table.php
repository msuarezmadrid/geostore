<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleBoxDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_box_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sale_box_transact_id');
            $table->text('filename');
            $table->timestamps();
        });
        /*
        Comentado FK debido a que se realizó una investigación en torno a su no funcionamiento en el servidor de desarrollo, 
            y se determinó, por medio de gestor de base de datos y probando con medidas analogas, 
            que no es factible en este momento seguir invirtiendo esfuerzos en tener una FK en tabla asociada a la transacción,
            se escogió el UUID de transacción como FK prospectiva por que es unica por cada instancia de caja.
        La relación seguirá existiendo, pero será una relación no mantenida por base de datos, 
            sino que tendrá que ser mantenida por desarrolladores y documentación.
        Este mensaje de commit se repite en el archivo de migration donde se realizó el comentario. Merge asociado: !252

        */
        // Schema::table('sale_box_documents', function (Blueprint $table) {
        //     $table->foreign('sale_box_transact_id')->references('transact_id')->on('sale_box_details');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_box_documents');
    }
}
