<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyLessonTracking extends Model
{
    protected $table = 'daily_lesson_tracking';

    protected $fillable = [
        'student_id',
        'schedule_item_id',
        'tracking_date',
        'is_completed',
        'study_duration_minutes',
        'notes',
        'difficulty_level',
        'understanding_score'
    ];

    protected $casts = [
        'tracking_date' => 'date',
        'is_completed' => 'boolean',
        'study_duration_minutes' => 'integer',
        'understanding_score' => 'integer'
    ];

    /**
     * Öğrenci ilişkisi
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Program öğesi ilişkisi
     */
    public function scheduleItem(): BelongsTo
    {
        return $this->belongsTo(ScheduleItem::class);
    }

    /**
     * Belirli bir tarih için takip kayıtları
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('tracking_date', $date);
    }

    /**
     * Belirli bir öğrenci için takip kayıtları
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Tamamlanan dersler
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Zorluk seviyesi rengi
     */
    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty_level) {
            'kolay' => 'success',
            'orta' => 'warning',
            'zor' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Anlama puanı rengi
     */
    public function getUnderstandingColorAttribute()
    {
        if ($this->understanding_score >= 8) return 'success';
        if ($this->understanding_score >= 6) return 'warning';
        if ($this->understanding_score >= 4) return 'info';
        return 'danger';
    }
}