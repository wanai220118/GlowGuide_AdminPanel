<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qty',
        'category',
        'price',
        'description'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->product_number = 'PROD-' . now()->format('YmdHis');
        });
    }

    // protected $casts = [
    //     'image' = 'array'
    // ]
}
