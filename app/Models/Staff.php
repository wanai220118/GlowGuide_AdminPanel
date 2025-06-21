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
        'day',
        'slot1',
        'slot2',
        'slot3'
    ];

    protected $casts = [
        'working_days' => 'array',
    ];
    public static $specialistOptions = [
        'Aesthetic doctor',
        'Dermatologist',
        'Esthetician',
    ];

    // public function schedules()
    // {
    //     return $this->hasMany(StaffSchedule::class, 'staff_id', 'id');

    // }

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
