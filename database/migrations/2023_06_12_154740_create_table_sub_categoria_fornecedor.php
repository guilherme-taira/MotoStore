<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSubCategoriaFornecedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categoria_fornecedor', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("descricao");
            $table->unsignedBigInteger('categoria_principal');
            $table->foreign('categoria_principal')->references('id')->on('categorias_forncedores');
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
        Schema::dropIfExists('table_sub_categoria_fornecedor');
    }
}
