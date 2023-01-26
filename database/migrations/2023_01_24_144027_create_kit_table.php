<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('id_product_kit');
            $table->float('available_quantity');
            $table->float('acrescimo')->default(0);
            $table->float('desconto')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->integer('vendas')->default(0);
            $table->float('price')->default(0);
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('id_product_kit')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kit');
    }
}
