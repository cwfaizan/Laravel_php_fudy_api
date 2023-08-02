<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'quantity',
        'image_url',
        'category_id',
    ];

    protected $hidden = [
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
