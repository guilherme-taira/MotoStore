<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitProductVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('kit_product_variations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kit_id')->constrained('products')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        $table->string('sku');
        $table->decimal('custom_price', 10, 2); // Preço específico para esse kit
        $table->integer('quantity')->default(1);
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
        Schema::dropIfExists('kit_product_variations');
    }
}
