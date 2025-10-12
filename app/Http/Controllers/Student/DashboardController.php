<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\DailyLessonTracking;
use App\Http\Controllers\Student\AuthController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Middleware'i constructor'da değil, route'da tanımlayacağız
    }

    /**
     * Öğrenci dashboard
     */
    public function index()
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        // Bugünün tarihi
        $today = Carbon::today();
        
        // Öğrencinin aktif programlarını getir
        $schedules = $student->schedules()
            ->where('is_active', true)
            ->with(['scheduleItems.course', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->get();

        // Bugünkü dersleri getir
        $todayLessons = collect();
        foreach ($schedules as $schedule) {
            foreach ($schedule->scheduleItems as $item) {
                if ($item->day_of_week === strtolower($today->format('l'))) {
                    $todayLessons->push([
                        'id' => $item->id,
                        'schedule_name' => $schedule->name,
                        'course' => $item->course,
                        'topic' => $item->topic,
                        'subtopic' => $item->subtopic,
                        'notes' => $item->notes,
                        'tracking' => DailyLessonTracking::where('student_id', $student->id)
                            ->where('schedule_item_id', $item->id)
                            ->where('tracking_date', $today)
                            ->first()
                    ]);
                }
            }
        }

        // Bu hafta için istatistikler
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        
        $weeklyStats = [
            'total_lessons' => 0,
            'completed_lessons' => 0,
            'total_study_time' => 0,
            'average_understanding' => 0
        ];

        // Haftalık takip verilerini hesapla
        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {
            $dayLessons = collect();
            foreach ($schedules as $schedule) {
                foreach ($schedule->scheduleItems as $item) {
                    if ($item->day_of_week === strtolower($date->format('l'))) {
                        $dayLessons->push($item);
                    }
                }
            }
            
            $weeklyStats['total_lessons'] += $dayLessons->count();
            
            $completedToday = DailyLessonTracking::where('student_id', $student->id)
                ->where('tracking_date', $date)
                ->where('is_completed', true)
                ->count();
            
            $weeklyStats['completed_lessons'] += $completedToday;
            
            $studyTimeToday = DailyLessonTracking::where('student_id', $student->id)
                ->where('tracking_date', $date)
                ->sum('study_duration_minutes');
            
            $weeklyStats['total_study_time'] += $studyTimeToday;
        }

        // Ortalama anlama puanı
        $avgUnderstanding = DailyLessonTracking::where('student_id', $student->id)
            ->whereBetween('tracking_date', [$weekStart, $weekEnd])
            ->whereNotNull('understanding_score')
            ->avg('understanding_score');
        
        $weeklyStats['average_understanding'] = round($avgUnderstanding ?? 0, 1);

        return view('student.dashboard', compact('student', 'todayLessons', 'weeklyStats', 'today'));
    }

    /**
     * Önceki dersleri görüntüle
     */
    public function previousLessons(Request $request)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        // Tarih filtresi (varsayılan: son 30 gün)
        $days = $request->get('days', 30);
        $startDate = Carbon::today()->subDays($days);
        $endDate = Carbon::today();

        // Öğrencinin aktif programlarını getir
        $schedules = $student->schedules()
            ->where('is_active', true)
            ->with(['scheduleItems.course', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->get();

        // Geçmiş dersleri ve takip verilerini getir
        $previousLessons = collect();
        
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayLessons = collect();
            
            foreach ($schedules as $schedule) {
                foreach ($schedule->scheduleItems as $item) {
                    if ($item->day_of_week === strtolower($date->format('l'))) {
                        $tracking = DailyLessonTracking::where('student_id', $student->id)
                            ->where('schedule_item_id', $item->id)
                            ->where('tracking_date', $date)
                            ->first();
                        
                        $dayLessons->push([
                            'id' => $item->id,
                            'schedule_name' => $schedule->name,
                            'course' => $item->course,
                            'topic' => $item->topic,
                            'subtopic' => $item->subtopic,
                            'notes' => $item->notes,
                            'tracking' => $tracking,
                            'date' => $date->copy()
                        ]);
                    }
                }
            }
            
            if ($dayLessons->count() > 0) {
                $previousLessons->push([
                    'date' => $date->copy(),
                    'day_name' => $date->locale('tr')->dayName,
                    'lessons' => $dayLessons
                ]);
            }
        }

        // İstatistikler
        $stats = [
            'total_days' => $previousLessons->count(),
            'total_lessons' => $previousLessons->sum(function($day) {
                return $day['lessons']->count();
            }),
            'completed_lessons' => $previousLessons->sum(function($day) {
                return $day['lessons']->where('tracking.is_completed', true)->count();
            }),
            'total_study_time' => DailyLessonTracking::where('student_id', $student->id)
                ->whereBetween('tracking_date', [$startDate, $endDate])
                ->sum('study_duration_minutes'),
            'average_understanding' => DailyLessonTracking::where('student_id', $student->id)
                ->whereBetween('tracking_date', [$startDate, $endDate])
                ->whereNotNull('understanding_score')
                ->avg('understanding_score')
        ];

        return view('student.previous-lessons', compact('student', 'previousLessons', 'stats', 'days', 'startDate', 'endDate'));
    }
}