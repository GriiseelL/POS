<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
    ];

    public function products()
    {
        // Tambahkan foreign key 'id_category' di sini
        return $this->hasMany(Product::class, 'id_category');
    }
}