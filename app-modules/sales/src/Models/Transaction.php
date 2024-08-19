<?php

namespace Modules\Sales\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Crm\Models\Customer;

class Transaction extends Model
{
    use HasFactory;

    protected $with = ['transactionItems'];

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
     * Get transaction items
     * @return HasMany
     */
    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
