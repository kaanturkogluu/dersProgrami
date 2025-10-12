<?php

namespace App\Mail;

use App\Models\Student;
use App\Services\PHPMailerService;

class WelcomeStudentMail
{
    public $student;
    public $loginUrl;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->loginUrl = route('student.login');
        $this->subject = 'Hoş Geldiniz - ' . config('app.name');
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
        return view('emails.welcome-student', [
            'student' => $this->student,
            'loginUrl' => $this->loginUrl
        ])->render();
    }
}
