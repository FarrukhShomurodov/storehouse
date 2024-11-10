<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number',
        'product_id',
        'qr_code',
        'sold',
        'sold_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
