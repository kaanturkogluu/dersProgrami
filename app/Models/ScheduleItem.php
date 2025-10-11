<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleItem extends Model
{
    protected $fillable = [
        'schedule_id',
        'course_id',
        'topic_id',
        'subtopic_id',
        'day_of_week',
        'notes',
        'is_completed',
        'scheduled_date'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'scheduled_date' => 'date'
    ];

    /**
     * Program ilişkisi
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudentSchedule::class, 'schedule_id');
    }

    /**
     * Ders ilişkisi
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Konu ilişkisi
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Alt konu ilişkisi
     */
    public function subtopic(): BelongsTo
    {
        return $this->belongsTo(Subtopic::class);
    }

    /**
     * Belirli bir gün için öğeleri getir
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Tamamlanan öğeleri getir
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Bekleyen öğeleri getir
     */
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    /**
     * Gün adını Türkçe olarak getir
     */
    public function getDayNameAttribute()
    {
        $days = [
            'monday' => 'Pazartesi',
            'tuesday' => 'Salı',
            'wednesday' => 'Çarşamba',
            'thursday' => 'Perşembe',
            'friday' => 'Cuma',
            'saturday' => 'Cumartesi',
            'sunday' => 'Pazar'
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }

    /**
     * Süreyi saat:dakika formatında getir (artık kullanılmıyor)
     */
    public function getDurationFormattedAttribute()
    {
        return '-';
    }
}
