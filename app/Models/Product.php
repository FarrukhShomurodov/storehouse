<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'price',
        'qr_code'
    ];

    /**
     * @return void
     */
    public function generateQrCode(): void
    {
        $filePath = 'qrcodes/' . $this->sku . '.svg';
        $qrCode = QrCode::format('svg')->size(300)->generate(route('products.confirmSale', $this->id));

        Storage::disk('public')->put($filePath, $qrCode);

        $this->qr_code = 'storage/' . $filePath;
        $this->save();
    }
}
