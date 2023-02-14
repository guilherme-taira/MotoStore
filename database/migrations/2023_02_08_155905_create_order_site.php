<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_site', function (Blueprint $table) {
            $table->id();
            $table->string('numeropedido');
            $table->string('local');
            $table->float('valorVenda');
            $table->float('valorProdutos');
            $table->date('dataVenda');
            $table->timestamp('dataDivergencia')->nullable();
            $table->float('valorDivergencia');
            $table->string('cliente')->nullable();
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
        Schema::dropIfExists('order_site');
    }
}
