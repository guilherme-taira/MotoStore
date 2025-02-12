<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusEnvioToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financeiro', function (Blueprint $table) {
            // Garantindo que o campo é BIGINT UNSIGNED
            $table->unsignedBigInteger('status_envio')->nullable()->after('id');
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
            // Primeiro removemos a chave estrangeira, depois o campo
            $table->dropForeign(['status_envio']);
            $table->dropColumn('status_envio');
        });
    }
}
