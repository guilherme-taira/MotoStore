<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFornecedorIdToKitProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kit_product_variations', function (Blueprint $table) {
            $table->unsignedBigInteger('fornecedor_id')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kit_product_variations', function (Blueprint $table) {
            $table->dropColumn('fornecedor_id');
        });
    }
}
