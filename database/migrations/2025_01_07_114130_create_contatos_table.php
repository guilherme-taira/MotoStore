<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContatosTable extends Migration
{
    public function up()
    {
        Schema::create('contatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('integracao_bling_id'); // Chave estrangeira
            $table->string('nome');
            $table->string('email')->nullable();
            $table->string('celular')->nullable();
            $table->string('numeroDocumento')->nullable();
            $table->string('tipo');
            $table->string('situacao');
            $table->string('rg')->nullable();
            $table->string('cep');
            $table->string('endereco');
            $table->string('bairro');
            $table->string('municipio');
            $table->string('uf', 2);
            $table->string('numero');
            $table->string('complemento')->nullable();
            $table->timestamps();

            // Configurar a chave estrangeira
            $table->foreign('integracao_bling_id')->references('id')->on('integracao_bling')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contatos');
    }
}
