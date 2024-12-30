<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAffiliateFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('estoque_minimo_afiliado')->default(0); // Estoque Mínimo Afiliado
            $table->decimal('percentual_estoque', 5, 2)->default(0); // Percentual de Estoque (5 dígitos, 2 decimais)
            $table->integer('estoque_afiliado')->default(0); // Estoque do Afiliado
            $table->integer('min_unidades_kit')->default(0); // Mínimo de Unidades no Kit
            $table->string('acao')->nullable(); // Ação
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'estoque_minimo_afiliado',
                'percentual_estoque',
                'estoque_afiliado',
                'min_unidades_kit',
                'acao',
            ]);
        });
    }
}
