<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Thêm 4 cột lưu thông tin giao nhận và hiệu lực bảo hiểm
            $table->date('effective_date')->nullable()->comment('Ngày bắt đầu hiệu lực');
            $table->string('receiver_name')->nullable()->comment('Họ tên người nhận');
            $table->string('receiver_phone')->nullable()->comment('Số điện thoại người nhận');
            $table->string('shipping_address')->nullable()->comment('Địa chỉ giao ấn chỉ');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'effective_date',
                'receiver_name',
                'receiver_phone',
                'shipping_address',
            ]);
        });
    }
};
