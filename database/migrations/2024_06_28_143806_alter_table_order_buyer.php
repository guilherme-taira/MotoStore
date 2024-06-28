<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOrderBuyer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_site', function(Blueprint $table){
            $table->text('buyer')->nullable(); // Adiciona o campo buyer como TEXT, permitindo valores nulos
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_site', function(Blueprint $table){
            $table->dropColumn('buyer');
        });
    }
}
