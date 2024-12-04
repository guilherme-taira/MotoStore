<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsIntegradoToIntegracaoBlingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integracao_bling', function (Blueprint $table) {
            $table->char('isIntegrado', 1)->default('0')->after('link'); // Adiciona o campo como CHAR que aceita boolean
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('integracao_bling', function (Blueprint $table) {
            $table->dropColumn('isIntegrado'); // Remove o campo
        });
    }
}
