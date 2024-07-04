<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('front.cart');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productId = $request->input('product_id');
        // dapatkan nama buyer yang sedang menambahkan barang ke cart
        $user = Auth::user();
        // cek apakah barang yang sebelumnya sudah pernah di masukkan ke cart apa belum
        $existingCartItem = Cart::where('user_id', $user->id)->where('product_id', $productId)->first();
        // jika produk sebelumnya ada, maka ke ke halaman index dari cart
        if ($existingCartItem) {
            return redirect()->route('carts.index');
        }
        // membuka koneksi ke DB
        DB::beginTransaction();

        try {
            // mendapatkan data dari produk dan masukkan ke dalam tabel cart lalu update atau create
            $cart = Cart::updateOrCreate([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            // save data yang didapat ke database
            $cart->save();
            // commit ke DB
            DB::commit();
            // arahkan buyer ke halaman index dari carts
            return redirect()->route('carts.index');
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
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
