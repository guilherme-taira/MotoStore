<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
           Schema::create('variacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meli_variation_id')->unique();
            $table->string('sku')->index();
            $table->string('id_mercadolivre');
            $table->decimal('price', 10, 2);
            $table->integer('available_quantity');
            $table->json('attribute_combinations')->nullable();
            $table->json('picture_ids')->nullable();
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
        Schema::dropIfExists('variacoes');
    }
}
