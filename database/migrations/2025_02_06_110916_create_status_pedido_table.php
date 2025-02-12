<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusPedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status_pedido', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_app_id');
            $table->unsignedBigInteger('order_site_id');
            $table->string('etiqueta');
            $table->timestamps();

            // Definindo as chaves estrangeiras
            $table->foreign('status_app_id')->references('id')->on('status_app')->onDelete('cascade');
            $table->foreign('order_site_id')->references('id')->on('order_site')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_pedido');
    }
}
