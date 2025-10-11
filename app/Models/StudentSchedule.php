<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSchedule extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'areas',
        'start_date',
        'end_date',
        'is_active',
        'description',
        'schedule_data'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'areas' => 'array',
        'schedule_data' => 'array'
    ];

    /**
     * Öğrenci ilişkisi
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Program öğeleri ilişkisi
     */
    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class, 'schedule_id');
    }

    /**
     * Aktif programları getir
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Belirli bir alan için programları getir
     */
    public function scopeForArea($query, $area)
    {
        return $query->where('area', $area);
    }

    /**
     * Program süresini hesapla (gün cinsinden)
     */
    public function getDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Program durumunu getir
     */
    public function getStatusAttribute()
    {
        $today = now()->toDateString();
        
        if ($this->start_date->toDateString() > $today) {
            return 'upcoming';
        } elseif ($this->end_date->toDateString() < $today) {
            return 'completed';
        } else {
            return 'active';
        }
    }
}
