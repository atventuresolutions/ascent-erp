<?php

namespace Modules\Crm\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'firstname',
        'lastname',
        'mobile_number',
        'telephone_number',
        'email',
        'address'
    ];

    /**
     * Override model boot method
     */
    protected static function boot(): void
    {
        parent::boot();

        // Generate a unique reference code for the customer before saving
        static::creating(function ($customer) {
            $customer->reference = self::generateReferenceCode();
        });
    }

    /**
     * Generate a unique reference code for the customer
     *
     * @return string
     */
    public static function generateReferenceCode()
    {
        // Generate a unique alpha-numeric reference code, 9 characters long
        $reference = strtoupper(substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 9)), 0, 9));

        // Check if the generated reference code already exists in the database
        $isExisting = self::whereReference($reference)->exists();
        if ($isExisting)
            return self::generateReferenceCode();
        else
            return $reference;
    }
}
