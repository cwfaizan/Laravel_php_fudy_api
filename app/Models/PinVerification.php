<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinVerification extends Model
{
    use HasFactory;

    protected $primaryKey = ['item', 'pin_type'];
    protected $table = "pin_verifications";
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pin_type',
        'pin',
        'pin_verified',
        'pin_verified_at',
        'item',
        'expired_at',
    ];
}
