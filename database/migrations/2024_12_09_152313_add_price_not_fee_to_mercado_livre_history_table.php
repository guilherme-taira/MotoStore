<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceNotFeeToMercadoLivreHistoryTable extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mercado_livre_history', function (Blueprint $table) {
            // $table->double('priceNotFee', 8, 2)->nullable();
             // Para PostgreSQL, substitua a linha acima por:
            $table->float('priceNotFee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mercado_livre_history', function (Blueprint $table) {
            $table->dropColumn('priceNotFee');
        });
    }
}
