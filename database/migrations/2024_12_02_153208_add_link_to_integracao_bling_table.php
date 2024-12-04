<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkToIntegracaoBlingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integracao_bling', function (Blueprint $table) {
            $table->string('link')->nullable()->after('client_secret'); // Adiciona o campo "link"
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
            $table->dropColumn('link'); // Remove o campo "link"
        });
    }
}
