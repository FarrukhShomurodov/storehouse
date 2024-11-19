<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateProductUnit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $product;
    protected $quantity;


    /**
     * @param Product $product
     * @param int $quantity
     */
    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function handle()
    {
        $qrCodeTemplate = QrCode::format('png')->size(300);

        Log::info($this->quantity);

        for ($i = 0; $i < $this->quantity; $i++) {
            $uuid = (string)Str::uuid();
            $filePath = $this->product->name . ' qrcodes/' . $uuid . '.png';

            $qrCode = $qrCodeTemplate->generate(
                secure_url("confirm/$uuid/sell")
            );

            ProductUnit::query()->create([
                'product_id' => $this->product->id,
                'serial_number' => $uuid,
                'qr_code' => $filePath
            ]);

            Storage::disk('public')->put($filePath, $qrCode);
        }
    }
}
