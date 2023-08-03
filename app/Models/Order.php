<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'maize_id',
        'recipe_id',
        'quantity',
        'unit_price',
        'user_id',
        'is_completed',
    ];
}
