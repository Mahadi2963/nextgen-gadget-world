<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MobileModel extends Model
{
    protected $table = 'models';

    protected $fillable = [
        'brand_id',
        'model_name',
        'buy_price',
        'quantity',
        'status',
        'image',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
