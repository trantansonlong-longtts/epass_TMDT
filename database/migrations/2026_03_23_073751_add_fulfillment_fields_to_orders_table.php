<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('insurance_link')->nullable()->comment('Link ấn chỉ điện tử');
            $table->string('tracking_code')->nullable()->comment('Mã vận đơn ViettelPost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['insurance_link', 'tracking_code']);
        });
    }
};
