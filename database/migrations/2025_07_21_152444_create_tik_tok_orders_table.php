<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTikTokOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tik_tok_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('status');
            $table->string('buyer_name')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->json('items')->nullable();
            $table->string('shipping_status')->nullable();
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
        Schema::dropIfExists('tik_tok_orders');
    }
}
