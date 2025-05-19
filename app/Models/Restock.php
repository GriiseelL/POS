<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_product",
        "tipe",
        "quantity"
    ];

    protected $table = 'riwayat_stock';
}
