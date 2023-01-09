<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category_id');
            $table->string('listing_type_id');
            $table->string('buying_mode')->default('buy_it_now');
            $table->string('condition')->default('new');
            $table->string('brand')->default('GENÉRICO');
            $table->string('gtin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products',function(Blueprint $table){
            $table->string('category_id');
            $table->string('listing_type_id');
            $table->string('buying_mode')->default('buy_it_now');
            $table->string('condition')->default('new');
            $table->string('brand')->default('GENÉRICO');
            $table->string('gtin');
        });

    }
}
