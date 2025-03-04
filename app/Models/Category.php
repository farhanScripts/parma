<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // $guarded fungsinya buat proteksi ke field id di table agar gabisa diisi secara langsung, tapi field lainnya boleh
    protected $guarded = [
        'id'
    ];

    public function product(){
        return $this->hasMany(Product::class);
    }
}
