<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funko extends Model {
    use HasFactory;

    //campos que se pueden llenar con create() o update()
    protected $fillable= [
        'name',
        'era',
        'image_path',
        'description',
        'price',
        'stock',
        'category_id',
    ];

    // Relación con el modelo Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
