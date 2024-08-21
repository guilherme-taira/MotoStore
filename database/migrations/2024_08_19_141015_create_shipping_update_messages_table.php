<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingUpdateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_update_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_update_id'); // Chave estrangeira para shipping_update
            $table->string('mensagem'); // Campo para mensagem
            $table->timestamps();

            // Definindo a relação de chave estrangeira
            $table->foreign('shipping_update_id')
                  ->references('id')
                  ->on('shipping_updates')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_update_messages');
    }
}
