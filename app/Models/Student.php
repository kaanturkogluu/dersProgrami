<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'student_number',
        'address',
        'is_active'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Öğrenci programları ilişkisi
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(StudentSchedule::class);
    }

    /**
     * Aktif programları getir
     */
    public function activeSchedules(): HasMany
    {
        return $this->hasMany(StudentSchedule::class)->where('is_active', true);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
