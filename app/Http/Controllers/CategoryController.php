<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ambil semua data yang ada di tabel kategori
        $categories = Category::all();

        // masukkan data yang telah didapat ke halaman index
        return view('admin.categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi inputan oleh pengguna
        $validated = $request->validate([
            // buat field name itu required dan tipe data string serta max char 255
            'name' => 'required|string|max:255',
            // buat field icon itu required dan tipe image yakni png, jpg, atau svg
            'icon' => 'required|image|mimes:png,jpg,svg'
        ]);

        //persiapkan Database untuk menerima transaksi, semua field harus terisi kalau tidak rollBack()
        DB::beginTransaction();

        try {
            // cek apakah user udah masukin file gambar icon apa belum
            if ($request->hasFile('icon')) {
                // generate path untuk menyimpan icon dimana
                $iconPath = $request->file('icon')->store('category_icons', 'public');
                $validated['icon'] = $iconPath;
            }
            // bikin slug berdasarkan dari inputan name category di create.blade.php
            $validated['slug'] = Str::slug($request->name);
            // misal obat sakit -> obat-sakit

            // simpan data category yang baru ke database categories melalui model Category
            $newCategory = Category::create($validated);

            // kalau semuanya lengkap, simpan perubahan pada database
            DB::commit();

            // redirect ke route index kategori
            return redirect()->route('admin.categories.index');
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // masukkan data yang telah didapat ke halaman edit.blade.php
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // validasi inputan oleh pengguna
        $validated = $request->validate([
            // buat field name itu required dan tipe data string serta max char 255
            // karena nama dari kategori mungkin gadiganti, ganti dari require jadi sometimes
            'name' => 'sometimes|string|max:255',
            // buat field icon itu required dan tipe image yakni png, jpg, atau svg
            // karena icon mungkin gadiubah sama user ganti dari required jadi sometimes
            'icon' => 'sometimes|image|mimes:png,jpg,svg'
        ]);

        //persiapkan Database untuk menerima transaksi, semua field harus terisi kalau tidak rollBack()
        DB::beginTransaction();

        try {
            // cek apakah user udah masukin file gambar icon apa belum
            if ($request->hasFile('icon')) {
                // generate path untuk menyimpan icon dimana
                $iconPath = $request->file('icon')->store('category_icons', 'public');
                $validated['icon'] = $iconPath;
            }
            // bikin slug berdasarkan dari inputan name category di create.blade.php
            $validated['slug'] = Str::slug($request->name);
            // misal obat sakit -> obat-sakit

            // simpan data category yang baru ke database categories melalui model Category
            $category->update($validated);

            // kalau semuanya lengkap, simpan perubahan pada database
            DB::commit();

            // redirect ke route index kategori
            return redirect()->route('admin.categories.index');
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
    public function destroy(Category $category)
    {
        try {
            // hapus data kategori yang dipilih dengan metode hard delete
            $category->delete();
            // balik ke halaman index category
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
