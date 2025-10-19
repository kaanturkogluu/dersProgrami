<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    protected $fillable = [
        'name',
        'description',
        'course_id',
        'order_index',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subtopics(): HasMany
    {
        return $this->hasMany(Subtopic::class)->orderBy('order_index');
    }

    public function topicTrackings(): HasMany
    {
        return $this->hasMany(TopicTracking::class);
    }

    public function questionAnalyses(): HasMany
    {
        return $this->hasMany(QuestionAnalysis::class);
    }
}
