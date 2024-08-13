<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStockHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_stock_id',
        'quantity',
        'type',
        'system',
        'notes',
    ];

    /**
     * Get owning inventory stock
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventoryStock()
    {
        return $this->belongsTo(
            InventoryStock::class,
            'inventory_stock_id',
            'id'
        );
    }
}
