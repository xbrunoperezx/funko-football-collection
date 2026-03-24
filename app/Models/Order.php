<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'address',
        'total',
        'status',
    ];

    // un pedido pertenece a un usuario (puede ser null)
    public function user() {
        return $this->belongsTo(User::class);
    }
}