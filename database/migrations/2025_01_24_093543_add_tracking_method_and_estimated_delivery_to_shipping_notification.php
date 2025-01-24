<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingMethodAndEstimatedDeliveryToShippingNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_notification', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('substatus'); // Número de rastreamento
            $table->string('tracking_method')->nullable()->after('tracking_number'); // Método de rastreamento
            $table->date('estimated_delivery_extended')->nullable()->after('tracking_method'); // Data de entrega estendida
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_notification', function (Blueprint $table) {
            $table->dropColumn('tracking_method');
            $table->dropColumn('tracking_number');
            $table->dropColumn('estimated_delivery_extended');

        });
    }
}
