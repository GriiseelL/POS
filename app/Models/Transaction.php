<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "product",
        "price",
        "sub_total",
        "total",
        "quantity",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }
}