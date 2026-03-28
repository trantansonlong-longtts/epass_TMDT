<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('description');
        });

        $products = DB::table('products')->orderBy('category')->orderBy('name')->get(['id']);

        foreach ($products as $index => $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update(['sort_order' => $index + 1]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
