<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFinanceiro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financeiro', function (Blueprint $table) {
            $table->json('detalhes_transacao')->nullable(); // Coluna JSON nullable sem valor padrão
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financeiro', function (Blueprint $table) {
            $table->dropColumn('detalhes_transacao'); // Coluna para armazenar JSON
        });
    }
}
