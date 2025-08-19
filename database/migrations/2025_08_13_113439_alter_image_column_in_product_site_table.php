<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterImageColumnInProductSiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_site', function (Blueprint $table) {
            // Aumenta o tamanho da coluna 'image' para 500 caracteres
            // Ou use ->change() para alterar o tipo de coluna
            $table->string('image', 500)->change();

            // Alternativa: Mude o tipo para 'text' (sem limite)
            // $table->text('image')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_site', function (Blueprint $table) {
            // Reverte a alteração no rollback
            // Volte ao tamanho original, se souber qual era
            $table->string('image', 191)->change();
        });
    }
}
