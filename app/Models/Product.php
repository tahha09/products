<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'category_id',
        'image',
        'stock_quantity',
        'is_active',
        'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
