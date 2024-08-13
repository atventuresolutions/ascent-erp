<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'current',
        'reorder_point',
        'reorder_quantity',
    ];

    /**
     * Get the inventory item that owns the stock.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventoryItem()
    {
        return $this->belongsTo(
            InventoryItem::class,
            'inventory_item_id',
            'id'
        );
    }

    /**
     * Get the inventory stock histories owned
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventoryStockHistories()
    {
        return $this->hasMany(
            InventoryStockHistory::class,
            'inventory_stock_id',
            'id'
        );
    }
}
