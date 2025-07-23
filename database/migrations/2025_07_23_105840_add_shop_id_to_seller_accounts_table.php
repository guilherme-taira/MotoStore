<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopIdToSellerAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seller_accounts', function (Blueprint $table) {
            $table->string('shop_id')->nullable()->after('shop_cipher');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seller_accounts', function (Blueprint $table) {
            $table->dropColumn('shop_id');
        });
    }
}
