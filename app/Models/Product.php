<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "id_category",
        "price",
        "stock",
        "photo",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

}