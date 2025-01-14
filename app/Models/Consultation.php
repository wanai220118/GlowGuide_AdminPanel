<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultation extends Model
{
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    use HasFactory;

    protected $fillable = [
        'consultation_name',
        'notes',
        'patient_id',
        'staff_id',
        'cons_date',
        'cons_time'
    ];

    protected $casts = [
        // 'price' => MoneyCast::class,
    ];
}
