<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiktokProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiktok_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('local_product_id')->nullable()->index();
            $table->string('tiktok_product_id')->unique()->index();
            $table->string('shop_id')->nullable()->index();
            $table->string('shop_cipher')->nullable();
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->decimal('price', 14, 2)->nullable();
            $table->json('raw_response')->nullable();
            $table->json('warnings')->nullable();
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
        Schema::dropIfExists('tiktok_products');
    }
}
