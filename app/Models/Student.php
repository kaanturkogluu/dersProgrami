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
        'password',
        'phone',
        'birth_date',
        'student_number',
        'address',
        'notes',
        'is_active',
        'admin_id'
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

    /**
     * Öğrencinin admin'i
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Konu takip kayıtları
     */
    public function topicTrackings()
    {
        return $this->hasMany(TopicTracking::class);
    }

    /**
     * Soru analizleri
     */
    public function questionAnalyses()
    {
        return $this->hasMany(QuestionAnalysis::class);
    }
}
