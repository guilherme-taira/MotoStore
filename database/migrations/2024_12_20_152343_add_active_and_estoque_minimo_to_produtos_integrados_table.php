<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveAndEstoqueMinimoToProdutosIntegradosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos_integrados', function (Blueprint $table) {
            $table->boolean('active')->default(true); // Campo 'active' com valor padrão true
            $table->integer('estoque_minimo')->default(0); // Campo 'estoque_minimo' com valor padrão 0
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
            $table->dropColumn(['active', 'estoque_minimo']);
        });
    }
}
