<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddTrackingFieldsToShippingUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_updates', function (Blueprint $table) {
            $table->string('id_rastreio')->nullable();
            $table->dateTime('criado_rastreio')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_updates', function (Blueprint $table) {
            $table->dropColumn(['id_rastreio', 'criado_rastreio']);
        });
    }
}
