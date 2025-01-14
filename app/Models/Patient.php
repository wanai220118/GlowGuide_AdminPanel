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
        'date_of_birth',
        'name',
        'email',
        'password',
        'phone_No',
        'gender',
        'image'
    ];
}
