<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBancario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bancario', function (Blueprint $table) {
            $table->id();
            $table->string('Banco')->default(0);
            $table->string('agencia')->default(0);
            $table->string('conta')->default(0);
            $table->string('nome');
            $table->string('cpf');
            $table->string('cnpj');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bancario');
    }
}
