<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Crm\Models\Customer;

class Order extends Model
{
    use HasFactory;

    protected $with = ['orderItems'];

    protected $fillable = [
        'customer_id',
        'status',
        'total',
        'discount',
        'tax',
        'shipping',
        'grand_total',
        'notes'
    ];

    /**
     * Get owning customer
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get order items
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
