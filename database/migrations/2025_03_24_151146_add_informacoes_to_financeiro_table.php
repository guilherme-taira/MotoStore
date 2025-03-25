<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInformacoesToFinanceiroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financeiro', function (Blueprint $table) {
            $table->text('informacoes')->nullable(); // ou após outra coluna, se desejar
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
            $table->dropColumn('informacoes');
        });
    }
}
