<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntegratedProductTypeToSalesReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('sales_report', function (Blueprint $table) {
            // Adiciona a nova coluna, por exemplo, 'produtos_integrados' ou 'tiktok_products'
            $table->string('integrated_product_type')->nullable()->after('integrated_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('sales_report', function (Blueprint $table) {
            $table->dropColumn('integrated_product_type');
        });
    }
}
