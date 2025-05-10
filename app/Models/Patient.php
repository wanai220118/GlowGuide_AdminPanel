<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model //
{
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'date_of_birth',
        'gender',
        'email',
        'phone_No',
        'address',
        'registration_date'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->patient_number = 'PAT-' . now()->format('YmdHis');
        });
    }
}
