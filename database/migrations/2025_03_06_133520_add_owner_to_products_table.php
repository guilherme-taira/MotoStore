<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnerToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Adiciona a coluna 'owner' que é uma chave estrangeira para a tabela 'users'
            $table->unsignedBigInteger('owner')->nullable()->after('id');

            // Define a restrição de chave estrangeira
            $table->foreign('owner')
                ->references('id')->on('users')
                ->onDelete('set null'); // Se o usuário for deletado, define o valor como nulo
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Primeiro remove a chave estrangeira
            $table->dropForeign(['owner']);
            // Em seguida, remove a coluna
            $table->dropColumn('owner');
        });
    }
}
