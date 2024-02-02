<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUserFrete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users',function(Blueprint $table){
            $table->string('phone')->default("0");;
            $table->string('cpf')->default("0");
            $table->string('cnpj')->default("0");
            $table->text('address')->default("");;
            $table->text('complemento')->default("");;
            $table->string('numero')->default("0");;
            $table->string('bairro')->default("0");;
            $table->string('cidade')->default("0");;
            $table->string('id_pais')->default('BR');
            $table->string('cep')->default("0");;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users',function(Blueprint $table){
            $table->dropColumn('phone');
            $table->dropColumn('cpf')->default("0");
            $table->dropColumn('cnpj')->default("0");
            $table->dropColumn('address');
            $table->dropColumn('complemento');
            $table->dropColumn('numero');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('id_pais')->default('BR');
            $table->dropColumn('cep');
        });
    }
}
