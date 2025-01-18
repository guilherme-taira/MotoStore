<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGlobalMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // mysql
        // Schema::create('global_messages', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('title')->collation('utf8mb4_unicode_ci'); // Atualiza para suportar emojis
        //     $table->text('content')->collation('utf8mb4_unicode_ci'); // Atualiza para suportar emojis
        //     $table->timestamp('start_at')->nullable(); // Permitir nulo para evitar erro
        //     $table->timestamp('end_at')->nullable(); // Permitir nulo para evitar erro
        //     $table->timestamps(); // Campos created_at e updated_at
        // });

        // postgree

        Schema::create('global_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Remove o collation incompatível
            $table->text('content'); // Remove o collation incompatível
            $table->timestamp('start_at')->nullable(); // Permitir nulo para evitar erro
            $table->timestamp('end_at')->nullable(); // Permitir nulo para evitar erro
            $table->timestamps(); // Campos created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_messages');
    }
}
