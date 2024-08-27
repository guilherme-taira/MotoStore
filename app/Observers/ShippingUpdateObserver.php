<?php

namespace App\Observers;

use App\Models\ShippingUpdate;

class ShippingUpdateObserver
{


    /**
     * Handle the ShippingUpdate "saving" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function saving(ShippingUpdate $shippingUpdate)
    {
        // Pegue todos os campos com prefixo 'was_'
        $wasFields = [
            'was_damaged',
            'was_delivered',
            'was_delivered_to_sender',
            'was_forwarded',
            'was_fulfilled',
            'was_misplaced',
            'was_refused',
            'was_returned',
            'was_scheduled',
        ];

        // Identifique o campo que foi modificado
        foreach ($wasFields as $field) {
            if ($shippingUpdate->isDirty($field) && $shippingUpdate->$field == 1) {
                // Zere os outros campos
                foreach ($wasFields as $otherField) {
                    if ($otherField !== $field) {
                        $shippingUpdate->$otherField = 0;
                    }
                }
                break;
            }
        }
    }

    /**
     * Handle the ShippingUpdate "created" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function created(ShippingUpdate $shippingUpdate)
    {
        //
    }

    /**
     * Handle the ShippingUpdate "updated" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function updated(ShippingUpdate $shippingUpdate)
    {
        //
    }

    /**
     * Handle the ShippingUpdate "deleted" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function deleted(ShippingUpdate $shippingUpdate)
    {
        //
    }

    /**
     * Handle the ShippingUpdate "restored" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function restored(ShippingUpdate $shippingUpdate)
    {
        //
    }

    /**
     * Handle the ShippingUpdate "force deleted" event.
     *
     * @param  \App\Models\ShippingUpdate  $shippingUpdate
     * @return void
     */
    public function forceDeleted(ShippingUpdate $shippingUpdate)
    {
        //
    }
}
