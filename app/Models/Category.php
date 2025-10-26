<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
