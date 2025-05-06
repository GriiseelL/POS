<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_product extends Model
{
    use HasFactory;


    protected $fillable = [
        "id_product",
        "id_transaksi",
        "quantity",
    ];

    protected $table = 'transaction_product';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'id_product');
    }



}
