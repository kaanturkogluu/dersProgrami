<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\ScheduleItem;
use App\Services\PHPMailerService;
use Carbon\Carbon;

class DailyReminderMail
{
    public $student;
    public $todayLessons;
    public $loginUrl;
    public $today;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->today = Carbon::today();
        $this->loginUrl = route('student.login');
        $this->subject = 'Günlük Ders Hatırlatması - ' . $this->today->format('d.m.Y');
        
        // Bugünkü dersleri getir
        $this->todayLessons = ScheduleItem::whereHas('schedule', function($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('is_active', true);
        })
        ->where('day_of_week', $this->today->dayOfWeek)
        ->with(['course.category', 'topic', 'subtopic'])
        ->get();

        $this->body = $this->generateBody();
    }

    /**
     * Mail gönder
     */
    public function send()
    {
        $mailService = new PHPMailerService();
        
        return $mailService->sendMail(
            $this->student->email,
            $this->student->full_name,
            $this->subject,
            $this->body,
            true
        );
    }

    /**
     * Mail içeriğini oluştur
     */
    private function generateBody()
    {
        return view('emails.daily-reminder', [
            'student' => $this->student,
            'todayLessons' => $this->todayLessons,
            'loginUrl' => $this->loginUrl,
            'today' => $this->today
        ])->render();
    }
}
