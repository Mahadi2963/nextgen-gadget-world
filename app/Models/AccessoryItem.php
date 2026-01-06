<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessoryItem extends Model
{
    protected $fillable = [
        'type_id',
        'item_name',
        'buy_price',
        'quantity', // ✅ added
        'status',
        'image',
    ];

      protected $casts = [
        'quantity' => 'integer',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(AccessoryType::class, 'type_id');
    }
}
