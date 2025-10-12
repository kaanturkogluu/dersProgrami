<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Mail\DailyReminderMail;
use Illuminate\Console\Command;

class SendDailyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-daily-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily reminder emails to all active students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Günlük hatırlatma mailleri gönderiliyor...');

        $students = Student::where('is_active', true)->get();
        $successCount = 0;
        $errorCount = 0;

        $progressBar = $this->output->createProgressBar($students->count());
        $progressBar->start();

        foreach ($students as $student) {
            try {
                $reminderMail = new DailyReminderMail($student);
                $result = $reminderMail->send();
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $this->error("Mail gönderilemedi ({$student->full_name}): " . $result['message']);
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Mail gönderilemedi ({$student->full_name}): " . $e->getMessage());
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("Toplam {$successCount} öğrenciye mail gönderildi.");
        if ($errorCount > 0) {
            $this->warn("{$errorCount} mail gönderilemedi.");
        }

        return Command::SUCCESS;
    }
}
