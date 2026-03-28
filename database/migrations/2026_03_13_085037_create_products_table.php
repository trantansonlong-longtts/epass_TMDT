<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Loại xe / Tên gói bảo hiểm');
            $table->string('category')->default('INSURANCE')->comment('EPASS hoặc INSURANCE');
            $table->decimal('base_price', 12, 2)->comment('Phí chuẩn chưa VAT');
            $table->decimal('vat_price', 12, 2)->comment('Phí chuẩn +10% VAT');
            $table->decimal('selling_price', 12, 2)->comment('Phí thu khách (Đã giảm)');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
