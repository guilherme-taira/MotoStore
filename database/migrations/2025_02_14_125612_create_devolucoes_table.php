<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devolucoes', function (Blueprint $table) {
            $table->id();
            $table->string('rastreio')->unique();
            $table->unsignedBigInteger('id_venda');
            $table->unsignedBigInteger('id_user');
            $table->timestamp('data_recebimento')->nullable(); // Quando chegou
            $table->unsignedBigInteger('bipado_por')->nullable(); // Quem bipou
            $table->timestamps();

            // Chaves estrangeiras
            $table->foreign('id_venda')->references('id')->on('order_site')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bipado_por')->references('id')->on('users')->onDelete('set null');
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
            Schema::dropIfExists('devolucoes');
        });
    }
}
