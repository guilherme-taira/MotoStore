<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingUpdateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_updates', function (Blueprint $table) {
                $table->id();
                $table->string('id_shopify')->nullable();
                $table->string('rastreio')->nullable();
                $table->string('url_rastreio')->nullable();
                $table->boolean('isBrazil')->default(false);
                $table->string('id_mercadoLivre')->nullable();
                $table->string('id_user');
                $table->string('id_vendedor');
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
        Schema::dropIfExists('shipping_update');
    }
}
