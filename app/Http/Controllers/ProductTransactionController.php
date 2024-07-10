<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\ProductTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('buyer')) {
            $product_transactions = $user->product_transactions()->orderBy('id', 'DESC')->get();
        } else {
            $product_transactions = ProductTransaction::orderBy('id', 'DESC')->get();
        }
        return view('admin.product_transactions.index', [
            'product_transactions' => $product_transactions
        ]);
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
        //
        $user = Auth::user();

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'proof' => 'required|image|mimes:png,jpg,jpeg',
            'notes' => 'required|string|max:65535',
            'post_code' => 'required|integer',
            'phone_number' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {
            // ubah semua harga menjadi satuan cents berdasarkan dollar
            // 1 dollar = 100 cent;
            $subTotalCents = 0;
            $deliveryFeeCents = 10000 * 100;
            // dapatkan barang-barang yang sedang ada di carts
            $cartItems = $user->carts;
            foreach ($cartItems as $item) {
                // ambil harga product nya karena carts sudah mempunyai relasi ke product
                $subTotalCents += $item->product->price * 100;
            }
            // konversi harga tax (11%) ke integer
            $taxCents = (int)round(11 * $subTotalCents / 100);
            // konversi harga insurance (23%) ke integer
            $insuranceCents = (int)round(23 * $subTotalCents / 100);
            // hitung grand total cents 
            $grandTotalCents = $subTotalCents + $taxCents + $insuranceCents + $deliveryFeeCents;
            // konversi dari grand total cents ke rupiah
            $grandTotal = $grandTotalCents / 100;

            // catat user id yang sedang login
            $validated['user_id'] = $user->id;
            $validated['total_amount'] = $grandTotal;
            $validated['is_paid'] = false;

            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('payment_proofs', 'public');
                $validated['proof'] = $proofPath;
            }
            // masukkan data ke dalam DB
            $newTransaction = ProductTransaction::create($validated);

            // simpan tiap tiap harga produk nya ke tabel Transaction Detail
            foreach ($cartItems as $item) {
                TransactionDetail::create([
                    'product_transaction_id' => $newTransaction->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price
                ]);

                // ketika data dari tiap barang yang dipesan udah disimpan, hapus data barang yang dipesan karena udah disimpan di DB
                $item->delete();
            }

            DB::commit();

            return redirect()->route('product_transactions.index');
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
    public function show(ProductTransaction $productTransaction)
    {
        $productTransaction = ProductTransaction::with('transactionDetails.product')->find($productTransaction->id);
        return view('admin.product_transactions.details', [
            'product_transaction' => $productTransaction,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductTransaction $productTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductTransaction $productTransaction)
    {
        //
        $productTransaction->update([
            'is_paid' => true
        ]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductTransaction $productTransaction)
    {
        //
    }
}
