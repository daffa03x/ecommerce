<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{
    public function index()
    {
        $cacheKey = 'product_data';
        $cacheDuration = 120; // 120 detik = 2 menit

        // Cek apakah data sudah ada di cache
        $products = Cache::remember($cacheKey, $cacheDuration, function () {
            $data = Product::orderByDesc('id')->where('status','available')->where('stock','>',0)->take(value: 5)->get();
            return $data;
        });
        return view("index",compact("products"));
    }

    public function allProduct()
    {
        $cacheKey = 'product_data';
        $cacheDuration = 120; // 120 detik = 2 menit

        // Cek apakah data sudah ada di cache
        $products = Cache::remember($cacheKey, $cacheDuration, function () {
            $data = Product::orderByDesc("id")->where('status','available')->where('stock','>',0)->get();
            return $data;
        });
        return view("product.index",compact("products"));
    }

    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        return view("product.show",compact("product"));
    }
    public function checkout()
    {
        return view('checkout.index');
    }
}
