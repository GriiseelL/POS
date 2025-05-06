<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "transaction_code",
        // "product",
        "metode_pembayaran",
        "price",
        "sub_total",
        "total",
        "seller",
        "quantity",
    ];

    protected $table = 'transactions';

    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }
    public function details()
    {
        return $this->hasMany(Transaction_product::class, 'id_transaksi', 'id')->with('product');
    }


}