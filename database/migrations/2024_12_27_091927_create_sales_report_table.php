<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_site_id'); // Referência da tabela order_site
            $table->unsignedBigInteger('product_id'); // Referência da tabela products
            $table->unsignedBigInteger('integrated_product_id'); // Referência da tabela produtos_integrados
            $table->integer('quantity_sold'); // Quantidade vendida
            $table->integer('quantity_before'); // Quantidade anterior no estoque
            $table->integer('quantity_after'); // Quantidade atual no estoque
            $table->timestamps();

            // Relacionamentos
            $table->foreign('order_site_id')->references('id')->on('order_site')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('integrated_product_id')->references('id')->on('produtos_integrados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_report');
    }
}
