<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOrderSiteFrete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_site', function(Blueprint $table){
            $table->string('id_frete')->default("");
            $table->double('valorFrete')->default(0);
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
            $table->dropColumn('id_frete');
            $table->dropColumn('valorFrete');
        });
    }
}
