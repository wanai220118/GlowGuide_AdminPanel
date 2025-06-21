<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'category',
        'description',
        'price',
        'qty',
        'expriry_date',
        // 'inventory',
        'updated_at',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->product_number = 'PROD-' . now()->format('YmdHis');
        });
    }

    public function getInventoryStatusAttribute()
    {
        if ($this->qty > 100) {
            return 'High in Stock';
        } elseif ($this->qty > 0) {
            return 'Low in Stock';
        } else {
            return 'Out of Stock';
        }
    }

    public function getExpiryStatusAttribute()
    {
        if (!$this->expiry_date) return 'Unknown';

        $daysLeft = now()->diffInDays($this->expiry_date, false);

        if ($daysLeft < 0) {
            return 'Expired';
        } elseif ($daysLeft <= 30) {
            return 'Expiring Soon';
        } else {
            return 'Valid';
        }
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // protected $casts = [
    //     'image' = 'array'
    // ]
}
