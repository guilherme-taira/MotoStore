<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTiktokProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tiktok_products', function (Blueprint $table) {
            $table->string('priceNotFee')->nullable();
            $table->decimal('acrescimo_reais', 10, 2)->nullable();
            $table->decimal('acrescimo_porcentagem', 5, 2)->nullable();
            $table->decimal('desconto_reais', 10, 2)->nullable();
            $table->decimal('desconto_porcentagem', 5, 2)->nullable();
            $table->tinyInteger('isPorcem')->default(0);
            $table->decimal('precofixo', 10, 2)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->integer('estoque_minimo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('tiktok_products', function (Blueprint $table) {
            $table->dropColumn([
                'priceNotFee',
                'acrescimo_reais',
                'acrescimo_porcentagem',
                'desconto_reais',
                'desconto_porcentagem',
                'isPorcem',
                'precofixo',
                'active',
                'estoque_minimo'
            ]);
        });
    }
}
