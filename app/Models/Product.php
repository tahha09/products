<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Product extends Model
{
    use HasFactory, SoftDeletes; // Enable soft deletes

    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'category_id',
        'image',
        'stock_quantity',
        'is_active',
        'user_id',
    ];
    protected $dates = ['deleted_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
