<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }

    public function accessoryTypes(): HasMany
    {
        return $this->hasMany(AccessoryType::class);
    }
}
