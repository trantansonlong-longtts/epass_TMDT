<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Gom chung toàn bộ 8 thông tin trên Giấy đăng ký xe vào đây
            $table->string('owner_name')->nullable()->comment('Tên chủ xe');
            $table->string('owner_address')->nullable()->comment('Địa chỉ chủ xe');
            $table->string('plate_color')->nullable()->comment('Màu biển');
            $table->string('payload')->nullable()->comment('Trọng tải');
            $table->integer('seat_capacity')->nullable()->comment('Số chỗ ngồi');
            $table->string('chassis_number')->nullable()->comment('Số khung');
            $table->string('engine_number')->nullable()->comment('Số máy');
            $table->date('issue_date')->nullable()->comment('Ngày cấp đăng ký');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Lệnh xóa đồng loạt 8 cột này nếu cần rollback
            $table->dropColumn([
                'owner_name', 'owner_address', 'plate_color',
                'payload', 'seat_capacity', 'chassis_number',
                'engine_number', 'issue_date',
            ]);
        });
    }
};
