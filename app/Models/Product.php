<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'description',
        'price',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
