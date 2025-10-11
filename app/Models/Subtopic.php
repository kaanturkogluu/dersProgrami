<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtopic extends Model
{
    protected $fillable = [
        'name',
        'description',
        'topic_id',
        'order_index',
        'duration_minutes',
        'content',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
