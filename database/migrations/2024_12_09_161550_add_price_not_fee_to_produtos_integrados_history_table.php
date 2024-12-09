<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceNotFeeToProdutosIntegradosHistoryTable extends Migration
{
      /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos_integrados', function (Blueprint $table) {
            $table->string('priceNotFee')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos_integrados', function (Blueprint $table) {
            $table->string('priceNotFee')->nullable();
        });
    }
}
