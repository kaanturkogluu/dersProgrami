<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAnalysis extends Model
{
    protected $table = 'question_analysis';
    
    protected $fillable = [
        'student_id',
        'topic_id',
        'subtopic_id',
        'question_source',
        'question_year',
        'question_number',
        'difficulty',
        'result',
        'time_spent_seconds',
        'student_answer',
        'correct_answer',
        'explanation',
        'notes',
        'solved_at'
    ];

    protected $casts = [
        'question_year' => 'integer',
        'question_number' => 'integer',
        'time_spent_seconds' => 'integer',
        'solved_at' => 'date'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function subtopic(): BelongsTo
    {
        return $this->belongsTo(Subtopic::class);
    }

    // Helper methods
    public function isCorrect(): bool
    {
        return $this->result === 'correct';
    }

    public function isIncorrect(): bool
    {
        return $this->result === 'incorrect';
    }

    public function isEmpty(): bool
    {
        return $this->result === 'empty';
    }

    public function getTimeSpentFormatted(): string
    {
        $minutes = floor($this->time_spent_seconds / 60);
        $seconds = $this->time_spent_seconds % 60;
        
        if ($minutes > 0) {
            return "{$minutes}dk {$seconds}sn";
        }
        
        return "{$seconds}sn";
    }

    public function getDifficultyColor(): string
    {
        return match($this->difficulty) {
            'kolay' => 'success',
            'orta' => 'warning',
            'zor' => 'danger',
            default => 'secondary'
        };
    }

    public function getResultColor(): string
    {
        return match($this->result) {
            'correct' => 'success',
            'incorrect' => 'danger',
            'empty' => 'secondary',
            default => 'secondary'
        };
    }

    // Net hesaplama (3 yanlış 1 doğruyu götürür)
    public static function calculateNet($correct, $incorrect): float
    {
        return max(0, $correct - ($incorrect / 3));
    }

    // Yıldızlama sistemi (performansa göre)
    public static function getStarRating($correct, $total): int
    {
        if ($total == 0) return 0;
        
        $percentage = ($correct / $total) * 100;
        
        if ($percentage >= 90) return 5;      // 5 yıldız
        if ($percentage >= 80) return 4;      // 4 yıldız
        if ($percentage >= 70) return 3;      // 3 yıldız
        if ($percentage >= 60) return 2;      // 2 yıldız
        if ($percentage >= 50) return 1;      // 1 yıldız
        return 0;                             // 0 yıldız
    }

    // Yıldız rengi
    public static function getStarColor($stars): string
    {
        return match($stars) {
            5 => 'text-success',
            4 => 'text-primary',
            3 => 'text-info',
            2 => 'text-warning',
            1 => 'text-warning',
            default => 'text-muted'
        };
    }

    // Toplu soru analizi için helper metodlar
    public function getTotalQuestions(): int
    {
        return $this->question_number ?? 1;
    }

    public function getCorrectCount(): int
    {
        return (int) $this->student_answer ?? 0;
    }

    public function getIncorrectCount(): int
    {
        return (int) $this->explanation ?? 0;
    }

    public function getEmptyCount(): int
    {
        return (int) $this->notes ?? 0;
    }

    public function getNet(): float
    {
        return self::calculateNet($this->getCorrectCount(), $this->getIncorrectCount());
    }

    public function getSuccessRate(): float
    {
        $total = $this->getTotalQuestions();
        if ($total == 0) return 0;
        return round(($this->getCorrectCount() / $total) * 100, 1);
    }

    public function getInstanceStarRating(): int
    {
        return self::getStarRating($this->getCorrectCount(), $this->getTotalQuestions());
    }

    public function getStarColorClass(): string
    {
        return self::getStarColor($this->getInstanceStarRating());
    }
}
