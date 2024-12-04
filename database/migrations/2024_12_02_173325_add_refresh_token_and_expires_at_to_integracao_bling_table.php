<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefreshTokenAndExpiresAtToIntegracaoBlingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('integracao_bling', function (Blueprint $table) {
            $table->string('refresh_token')->nullable()->after('access_token'); // Campo para o refresh_token
            $table->timestamp('expires_at')->nullable()->after('refresh_token'); // Campo para a data de expiração
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
            $table->dropColumn('refresh_token');
            $table->dropColumn('expires_at');
        });
    }
}
