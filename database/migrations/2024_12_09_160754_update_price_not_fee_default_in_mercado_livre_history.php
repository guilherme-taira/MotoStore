<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdatePriceNotFeeDefaultInMercadoLivreHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Alteração direta no banco de dados
        DB::statement("ALTER TABLE mercado_livre_history MODIFY priceNotFee DOUBLE(8,2) DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE mercado_livre_history MODIFY priceNotFee DOUBLE(8,2) NULL");
    }
}
