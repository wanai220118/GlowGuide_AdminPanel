<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Staff extends Model
{
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    // public function patient(): HasMany
    // {
    //     return $this->hasMany(Patient::class);
    // }
    // protected $casts = [
    //     'specialist' => 'array',
    //     'availability' => 'boolean',
    // ];

    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'specialist',
        'availability'
    ];

    public static $specialistOptions = [
        'Aesthetic doctor',
        'Dermatologist',
        'Esthetician',
    ];

    // Optional: Add a mutator for safety
    // public function setSpecialistAttribute($value)
    // {
    //     if (in_array($value, self::$specialistOptions)) {
    //         $this->attributes['specialist'] = $value;
    //     } else {
    //         throw new \InvalidArgumentException("Invalid specialist value: $value");
    //     }
    // }
}
