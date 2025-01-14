<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    use HasFactory;

    protected $fillable = [
        'total',
        'status',
        'payment_method',
        'payment_status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->order_number = 'ORD-' . now()->format('YmdHis');
        });
    }
}
