<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        // ambil data produk dan akses ke kategori nya juga karena produk memiliki foregin key Category
        $products = Product::with('category')->orderBy('id', 'DESC')->take(6)->get();
        $categories = Category::all();
        return view('front.index', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function details(Product $product)
    {
        return view('front.details', [
            'product' => $product
        ]);
    }

    public function search(Request $request)
    {
        // dapatkan kata kunci untuk keyword yang udah di input pengguna dalam search bar di index
        $kata_kunci = $request->input('keyword');
        // ambil data dari DB Product yang mengandung kata kunci yang sudah di input sama pengguna
        $products = Product::where('name', 'LIKE', '%' . $kata_kunci . '%')->get();
        return view('front.search', [
            'products' => $products,
            'keyword' => $kata_kunci
        ]);
    }

    public function category(Category $category)
    {
        // ambil sejumlah produk berdasarkan dari kategori
        $products = Product::where('category_id', $category->id)->with('category')->get();

        return view('front.category', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
