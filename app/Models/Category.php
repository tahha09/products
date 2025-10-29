<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Category extends Model
{
    use SoftDeletes; // Enable soft deletes

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
        'user_id',
    ];

    // Ensure Laravel treats deleted_at as DateTime
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
