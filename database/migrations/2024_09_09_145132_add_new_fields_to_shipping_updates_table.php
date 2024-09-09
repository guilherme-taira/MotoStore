<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToShippingUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_updates', function (Blueprint $table) {
            $table->boolean('is_address_not_found')->default(false);
            $table->boolean('is_awaiting_withdrawal')->default(false);
            $table->boolean('is_delivery_attempt_failed')->default(true);
            $table->boolean('is_out_for_delivery')->default(false);
            $table->boolean('is_waiting_tax_payment')->default(false);
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
            $table->dropColumn([
                'is_address_not_found',
                'is_awaiting_withdrawal',
                'is_delivery_attempt_failed',
                'is_out_for_delivery',
                'is_waiting_tax_payment'
            ]);
        });
    }
}
