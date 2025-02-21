<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingIdAndDadosToDevolucoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devolucoes', function (Blueprint $table) {
            $table->string('shippingId')->nullable()->after('rastreio'); // Adiciona o campo shippingId
            $table->json('dados')->nullable()->after('shippingId'); // Adiciona o campo JSON dados
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolucoes', function (Blueprint $table) {
            $table->dropColumn(['shippingId', 'dados']);
        });
    }
}
