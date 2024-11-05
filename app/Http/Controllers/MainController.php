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
        $cacheDuration = 300; // 300 detik = 5 menit

        // Cek apakah data sudah ada di cache
        $products = Cache::remember($cacheKey, $cacheDuration, function () {
            $data = Product::orderByDesc('id')->where('status','published')->take(value: 5)->get();
            return $data;
        });
        return view("index",compact("products"));
    }

    public function allProduct()
    {
        $cacheKey = 'product_data';
        $cacheDuration = 300; // 300 detik = 5 menit

        // Cek apakah data sudah ada di cache
        $products = Cache::remember($cacheKey, $cacheDuration, function () {
            $data = Product::orderByDesc("id")->where("status","published")->get();
            return $data;
        });
        return view("product.index",compact("products"));
    }

    public function cart()
    {
        return view('cart');
    }
}
