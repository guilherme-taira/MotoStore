<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOrderMl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_site',function(Blueprint $table){
            $table->string('external_reference')->nullable("");
            $table->string('preferenceId')->nullable("");
            $table->string('status_mercado_livre')->nullable("");
            $table->string('id_pagamento')->nullable("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_site',function(Blueprint $table){
            $table->dropColumn('external_reference');
            $table->dropColumn('preferenceId');
            $table->dropColumn('status_mercado_livre');
            $table->dropColumn('id_pagamento');
        });
    }
}
