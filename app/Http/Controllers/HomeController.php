<?php

namespace App\Http\Controllers;

use App\Models\Product; // Gọi Model Product để kết nối với bảng products
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $homepageContent = SiteSetting::homepageContent();

        return view('home', compact('products', 'homepageContent'));
    }
}
