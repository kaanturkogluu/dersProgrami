<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Mail\WelcomeStudentMail;
use App\Mail\DailyReminderMail;
use App\Services\PHPMailerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MailController extends Controller
{
    /**
     * Mail gönderme sayfası
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if ($currentUser->isSuperAdmin()) {
            $students = Student::where('is_active', true)->orderBy('first_name')->get();
        } else {
            $students = Student::where('is_active', true)->where('admin_id', $currentUser->id)->orderBy('first_name')->get();
        }

        // Mail konfigürasyonu kontrolü
        $mailService = new PHPMailerService();
        $mailConfig = $mailService->getConfig();
        $isConfigured = $mailService->isConfigured();
        $configErrors = $mailService->getConfigErrors();
        
        return view('admin.mail.index', compact('students', 'mailConfig', 'isConfigured', 'configErrors'));
    }

    /**
     * Hoş geldiniz maili gönder
     */
    public function sendWelcome(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $currentUser = Auth::user();
        $student = Student::findOrFail($request->student_id);
        
        // Yetki kontrolü
        if (!$currentUser->isSuperAdmin() && $student->admin_id !== $currentUser->id) {
            return redirect()->route('admin.mail.index')
                ->with('error', 'Bu öğrenciye mail gönderme yetkiniz bulunmamaktadır.');
        }

        try {
            $welcomeMail = new WelcomeStudentMail($student);
            $result = $welcomeMail->send();
            
            if ($result['success']) {
                return redirect()->route('admin.mail.index')
                    ->with('success', 'Hoş geldiniz maili ' . $student->full_name . ' adresine başarıyla gönderildi.');
            } else {
                return redirect()->route('admin.mail.index')
                    ->with('error', 'Mail gönderilirken bir hata oluştu: ' . $result['message']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.mail.index')
                ->with('error', 'Mail gönderilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Günlük hatırlatma maili gönder
     */
    public function sendDailyReminder(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $currentUser = Auth::user();
        $student = Student::findOrFail($request->student_id);
        
        // Yetki kontrolü
        if (!$currentUser->isSuperAdmin() && $student->admin_id !== $currentUser->id) {
            return redirect()->route('admin.mail.index')
                ->with('error', 'Bu öğrenciye mail gönderme yetkiniz bulunmamaktadır.');
        }

        try {
            $reminderMail = new DailyReminderMail($student);
            $result = $reminderMail->send();
            
            if ($result['success']) {
                return redirect()->route('admin.mail.index')
                    ->with('success', 'Günlük hatırlatma maili ' . $student->full_name . ' adresine başarıyla gönderildi.');
            } else {
                return redirect()->route('admin.mail.index')
                    ->with('error', 'Mail gönderilirken bir hata oluştu: ' . $result['message']);
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.mail.index')
                ->with('error', 'Mail gönderilirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Tüm öğrencilere günlük hatırlatma gönder
     */
    public function sendDailyReminderToAll(Request $request)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencilere, normal admin sadece kendi öğrencilerine
        if ($currentUser->isSuperAdmin()) {
            $students = Student::where('is_active', true)->get();
        } else {
            $students = Student::where('is_active', true)->where('admin_id', $currentUser->id)->get();
        }

        $mailService = new PHPMailerService();
        
        // Öğrenci listesini hazırla
        $recipients = $students->map(function($student) {
            return [
                'email' => $student->email,
                'name' => $student->full_name
            ];
        })->toArray();

        // İlk öğrenci için mail içeriğini oluştur
        $firstStudent = $students->first();
        if ($firstStudent) {
            $reminderMail = new DailyReminderMail($firstStudent);
            $subject = $reminderMail->subject;
            $body = $reminderMail->body;
        } else {
            return redirect()->route('admin.mail.index')
                ->with('error', 'Gönderilecek öğrenci bulunamadı.');
        }

        // Toplu mail gönder
        $result = $mailService->sendBulkMail($recipients, $subject, $body);

        $message = "Toplam {$result['successCount']} öğrenciye günlük hatırlatma maili gönderildi.";
        if ($result['errorCount'] > 0) {
            $message .= " {$result['errorCount']} mail gönderilemedi.";
        }

        $errors = [];
        foreach ($result['results'] as $mailResult) {
            if (!$mailResult['success']) {
                $errors[] = $mailResult['name'] . ': ' . $mailResult['message'];
            }
        }

        return redirect()->route('admin.mail.index')
            ->with('success', $message)
            ->with('errors', $errors);
    }

    /**
     * Test maili gönder
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $mailService = new PHPMailerService();
            $result = $mailService->sendTestMail($request->test_email, 'Test Kullanıcı');
            
            if ($result['success']) {
                // Production'da email adresini gösterme
                $emailDisplay = config('app.debug') ? $request->test_email : 'belirtilen adrese';
                return redirect()->route('admin.mail.index')
                    ->with('success', 'Test maili ' . $emailDisplay . ' başarıyla gönderildi.');
            } else {
                return redirect()->route('admin.mail.index')
                    ->with('error', 'Test maili gönderilirken bir hata oluştu: ' . $result['message']);
            }
        } catch (\Exception $e) {
            $errorMessage = config('app.debug') ? $e->getMessage() : 'Test maili gönderilirken bir hata oluştu.';
            return redirect()->route('admin.mail.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Mail konfigürasyonunu test et
     */
    public function testConnection()
    {
        try {
            $mailService = new PHPMailerService();
            $result = $mailService->testConnection();
            
            if ($result['success']) {
                return redirect()->route('admin.mail.index')
                    ->with('success', 'SMTP bağlantısı başarılı!');
            } else {
                return redirect()->route('admin.mail.index')
                    ->with('error', 'SMTP bağlantısı başarısız: ' . $result['message']);
            }
        } catch (\Exception $e) {
            $errorMessage = config('app.debug') ? 'SMTP bağlantısı test edilemedi: ' . $e->getMessage() : 'SMTP bağlantısı test edilemedi.';
            return redirect()->route('admin.mail.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Yeni öğrenci oluşturulduğunda otomatik hoş geldiniz maili gönder
     */
    public static function sendWelcomeToNewStudent(Student $student)
    {
        try {
            $welcomeMail = new WelcomeStudentMail($student);
            $result = $welcomeMail->send();
            
            if ($result['success']) {
                if (config('app.debug')) {
                    Log::info('Welcome mail gönderildi: ' . $student->email);
                }
                return true;
            } else {
                if (config('app.debug')) {
                    Log::error('Welcome mail gönderilemedi: ' . $result['message']);
                }
                return false;
            }
        } catch (\Exception $e) {
            if (config('app.debug')) {
                Log::error('Welcome mail gönderilemedi: ' . $e->getMessage());
            }
            return false;
        }
    }
}
