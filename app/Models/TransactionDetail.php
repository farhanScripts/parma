<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    // $guarded fungsinya buat proteksi ke field id di table agar gabisa diisi secara langsung, tapi field lainnya boleh

    protected $guarded = [
        'id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productTransaction()
    {
        return $this->belongsTo(ProductTransaction::class);
    }
}
