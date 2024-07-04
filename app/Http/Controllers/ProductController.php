<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'DESC')->get();
        return view('admin.products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // munculin berbagai kategori yang ada di tabel kategori
        $categories = Category::all();
        return view('admin.products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //proses validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'photo' => 'required|image|mimes:png,jpg,svg,webp',
            'category_id' => 'required|integer',
            // about karena di tipe nya text, jangan dibatasi limit karakter nya
            'about' => 'required|string'
        ]);

         //persiapkan Database untuk menerima transaksi, semua field harus terisi kalau tidak rollBack()
        DB::beginTransaction();

        try {
            // cek apakah user udah masukin file gambar foto apa belum
            if ($request->hasFile('photo')) {
                // generate path untuk menyimpan foto dimana
                $photoPath =  $request->file('photo')->store('product_photos', 'public');
                $validated['photo'] = $photoPath;
            }
            // bikin slug berdasarkan dari inputan name produk di create.blade.php
            $validated['slug'] = Str::slug($request->name);
            // misal obat sakit -> obat-sakit
            // simpan data produk yang baru ke database produk melalui model produk
            $newProduct = Product::create($validated);

            // kalau semuanya lengkap, simpan perubahan pada database
            DB::commit();

            // redirect ke route index kategori
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            // perintah ke database kalau ada data yang ga lengkap atau cacat di rollback (jangan disimpan datanya)
            DB::rollBack();
            // trus kasih message ke user kalau datanya error (ada yang ga lengkap)
            $error = ValidationException::withMessages([
                'system_error' => ['System Error!' . $e->getMessage()]
            ]);

            throw $error;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', [
            "product" => $product,
            "categories" => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //proses validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'photo' => 'sometimes|image|mimes:png,jpg,svg,webp',
            'category_id' => 'required|integer',
            // about karena di tipe nya text, jangan dibatasi limit karakter nya
            'about' => 'required|string'
        ]);

         //persiapkan Database untuk menerima transaksi, semua field harus terisi kalau tidak rollBack()
        DB::beginTransaction();

        try {
            // cek apakah user udah masukin file gambar foto apa belum
            if ($request->hasFile('photo')) {
                // generate path untuk menyimpan foto dimana
                $photoPath =  $request->file('photo')->store('product_photos', 'public');
                $validated['photo'] = $photoPath;
            }
            // bikin slug berdasarkan dari inputan name produk di create.blade.php
            $validated['slug'] = Str::slug($request->name);
            // misal obat sakit -> obat-sakit
            // simpan data produk yang baru ke database produk melalui model produk
            $product->update($validated);

            // kalau semuanya lengkap, simpan perubahan pada database
            DB::commit();

            // redirect ke route index kategori
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            // perintah ke database kalau ada data yang ga lengkap atau cacat di rollback (jangan disimpan datanya)
            DB::rollBack();
            // trus kasih message ke user kalau datanya error (ada yang ga lengkap)
            $error = ValidationException::withMessages([
                'system_error' => ['System Error!' . $e->getMessage()]
            ]);

            throw $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
         try {
            // hapus data product yang dipilih dengan metode hard delete
            $product->delete();
            // balik ke halaman index product
            return redirect()->back();
        } catch (\Exception $e) {
            // perintah ke database kalau ada data yang ga lengkap atau cacat di rollback (jangan disimpan datanya)
            DB::rollBack();
            // trus kasih message ke user kalau datanya error (ada yang ga lengkap)
            $error = ValidationException::withMessages([
                'system_error' => ['System Error!' . $e->getMessage()]
            ]);

            throw $error;
        }
    }
}
