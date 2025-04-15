<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction_product extends Model
{
    use HasFactory;

    
    protected $fillable = [
        "id_prodct",
        "id_transaksi",
        "quantity",
    ];

    protected $table = 'transaction_product';
}