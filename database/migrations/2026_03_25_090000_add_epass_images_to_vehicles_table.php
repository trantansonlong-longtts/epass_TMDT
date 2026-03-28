<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('front_vehicle_image')->nullable()->after('registration_image');
            $table->string('inspection_image')->nullable()->after('front_vehicle_image');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'front_vehicle_image',
                'inspection_image',
            ]);
        });
    }
};
