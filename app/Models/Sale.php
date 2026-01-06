<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'product_type',
        'product_id',
        'quantity_sold',
        'selling_price',
        'sold_at',
    ];

    protected $casts = [
        'sold_at' => 'datetime',
        'selling_price' => 'decimal:2',
    ];

    public function mobile(): BelongsTo
    {
        return $this->belongsTo(MobileModel::class, 'product_id');
    }

    public function accessory(): BelongsTo
    {
        return $this->belongsTo(AccessoryItem::class, 'product_id');
    }

    /**
     * Safe accessor for Filament
     */
    public function getProductNameAttribute(): string
    {
        return match ($this->product_type) {
            'mobile' => $this->mobile?->model_name ?? '-',
            'accessory' => $this->accessory?->item_name ?? '-',
            default => '-',
        };
    }
}
