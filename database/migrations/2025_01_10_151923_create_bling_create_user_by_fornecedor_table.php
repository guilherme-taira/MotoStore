<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlingCreateUserByFornecedorTable extends Migration
{
    public function up()
    {
        Schema::create('bling_create_user_by_fornecedor', function (Blueprint $table) {
            $table->id(); // ID da tabela
            $table->unsignedBigInteger('contato_id'); // ID da tabela contatos
            $table->string('bling_id'); // ID do Bling
            $table->timestamps(); // Campos created_at e updated_at

            // Chave estrangeira para a tabela contatos
            $table->foreign('contato_id')->references('id')->on('contatos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bling_create_user_by_fornecedor');
    }
}
