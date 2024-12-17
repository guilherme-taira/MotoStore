<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('produtos_integrados', function (Blueprint $table) {
            // Adiciona os campos solicitados
            $table->decimal('acrescimo_reais', 10, 2)->nullable();
            $table->decimal('acrescimo_porcentagem', 5, 2)->nullable();
            $table->decimal('desconto_reais', 10, 2)->nullable();
            $table->decimal('desconto_porcentagem', 5, 2)->nullable();
            $table->boolean('isPorcem')->default(false);
            $table->decimal('precofixo', 10, 2)->nullable(); // Novo campo precofixo
        });
    }

    public function down()
    {
        Schema::table('produtos_integrados', function (Blueprint $table) {
            // Remove os campos ao reverter o migration
            $table->dropColumn([
                'acrescimo_reais',
                'acrescimo_porcentagem',
                'desconto_reais',
                'desconto_porcentagem',
                'isPorcem',
                'precofixo', // Remove o novo campo precofixo
            ]);
        });
    }

}
