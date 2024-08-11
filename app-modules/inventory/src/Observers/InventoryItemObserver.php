<?php

namespace Modules\Inventory\Observers;

use Modules\Inventory\Models\InventoryItem;

class InventoryItemObserver
{
    /**
     * Handle the InventoryItem "created" event.
     */
    public function created(InventoryItem $inventoryItem): void
    {
        // Create an inventory stock record for the new inventory item
        $inventoryItem->inventoryStock()->create();
    }

    /**
     * Handle the InventoryItem "updated" event.
     */
    public function updated(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "deleted" event.
     */
    public function deleted(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "restored" event.
     */
    public function restored(InventoryItem $inventoryItem): void
    {
        //
    }

    /**
     * Handle the InventoryItem "force deleted" event.
     */
    public function forceDeleted(InventoryItem $inventoryItem): void
    {
        //
    }
}
