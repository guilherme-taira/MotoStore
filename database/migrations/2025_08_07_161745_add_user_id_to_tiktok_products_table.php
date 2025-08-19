<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToTiktokProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('tiktok_products', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id')
                  ->comment('ID do usuário que criou o produto no TikTok');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('tiktok_products', function (Blueprint $table) {
            // Se criou foreign key, remova-a primeiro:
            $table->dropForeign(['user_id']);

            $table->dropColumn('user_id');
        });
    }
}
