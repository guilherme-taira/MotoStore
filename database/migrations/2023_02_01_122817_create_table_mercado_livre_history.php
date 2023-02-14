<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMercadoLivreHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mercado_livre_history', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('id_ml');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_mercado_livre_history');
    }
}
