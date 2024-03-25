<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutosIntegrados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos_integrados', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable("");
            $table->string('image')->nullable("");
            $table->string('id_mercadolivre')->nullable("");
            $table->foreignId('user_id')->nullable()->default(Auth::id());
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_integrados');
    }
}
