<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyIntegracaoBlingIdInContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contatos', function (Blueprint $table) {
            // Remover a chave estrangeira
            $table->dropForeign(['integracao_bling_id']);

            // Tornar o campo nullable
            $table->unsignedBigInteger('integracao_bling_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contatos', function (Blueprint $table) {
            // Reverter a alteração para deixar o campo como obrigatório
            $table->unsignedBigInteger('integracao_bling_id')->nullable(false)->change();

            // Restaurar a chave estrangeira
            $table->foreign('integracao_bling_id')
                ->references('id')
                ->on('integracao_bling')
                ->onDelete('cascade');
        });
    }
}
