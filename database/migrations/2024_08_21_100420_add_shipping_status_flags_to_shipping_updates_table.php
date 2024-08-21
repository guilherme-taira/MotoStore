<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingStatusFlagsToShippingUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_updates', function (Blueprint $table) {
            $table->boolean('was_damaged')->default(false);
            $table->boolean('was_delivered')->default(false);
            $table->boolean('was_delivered_to_sender')->default(false);
            $table->boolean('was_forwarded')->default(false);
            $table->boolean('was_fulfilled')->default(false);
            $table->boolean('was_misplaced')->default(false);
            $table->boolean('was_refused')->default(false);
            $table->boolean('was_returned')->default(false);
            $table->boolean('was_scheduled')->default(false);
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
                'was_damaged',
                'was_delivered',
                'was_delivered_to_sender',
                'was_forwarded',
                'was_fulfilled',
                'was_misplaced',
                'was_refused',
                'was_returned',
                'was_scheduled'
            ]);
        });
    }
}
