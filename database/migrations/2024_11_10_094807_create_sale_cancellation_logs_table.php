<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_cancellation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->text('reason')->default("Без причины.");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_cancellation_logs');
    }
};
