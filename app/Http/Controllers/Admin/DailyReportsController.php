<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\DailyLessonTracking;
use App\Models\ScheduleItem;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyReportsController extends Controller
{
    /**
     * Günlük raporlar ana sayfası
     */
    public function index(Request $request)
    {
        // Tarih parametresi (varsayılan: bugün)
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Öğrenci filtresi
        $studentFilter = $request->get('student_id');
        
        // Tüm öğrencileri getir
        $students = Student::where('is_active', true)->orderBy('first_name')->get();
        
        // Seçilen tarih için takip verilerini getir
        $query = DailyLessonTracking::with(['student', 'scheduleItem.course', 'scheduleItem.topic', 'scheduleItem.subtopic'])
            ->where('tracking_date', $selectedDate);
            
        if ($studentFilter) {
            $query->where('student_id', $studentFilter);
        }
        
        $trackings = $query->orderBy('created_at', 'desc')->get();
        
        // İstatistikler
        $stats = [
            'total_lessons' => ScheduleItem::whereHas('schedule', function($q) {
                $q->where('is_active', true);
            })->count(),
            'completed_lessons' => $trackings->where('is_completed', true)->count(),
            'total_study_time' => $trackings->sum('study_duration_minutes'),
            'average_understanding' => $trackings->whereNotNull('understanding_score')->avg('understanding_score'),
            'students_with_activity' => $trackings->pluck('student_id')->unique()->count()
        ];
        
        // Öğrenci bazında özet
        $studentSummary = $trackings->groupBy('student_id')->map(function($studentTrackings) {
            $student = $studentTrackings->first()->student;
            return [
                'student' => $student,
                'total_lessons' => $studentTrackings->count(),
                'completed_lessons' => $studentTrackings->where('is_completed', true)->count(),
                'total_study_time' => $studentTrackings->sum('study_duration_minutes'),
                'average_understanding' => $studentTrackings->whereNotNull('understanding_score')->avg('understanding_score'),
                'difficulty_breakdown' => $studentTrackings->groupBy('difficulty_level')->map->count()
            ];
        });
        
        return view('admin.daily-reports.index', compact(
            'selectedDate', 
            'students', 
            'trackings', 
            'stats', 
            'studentSummary',
            'studentFilter'
        ));
    }
    
    /**
     * Öğrenci detay raporu
     */
    public function studentDetail(Request $request, Student $student)
    {
        // Tarih aralığı parametreleri
        $startDate = $request->get('start_date', Carbon::today()->subDays(7)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        // Öğrencinin takip verilerini getir
        $trackings = DailyLessonTracking::with(['scheduleItem.course', 'scheduleItem.topic', 'scheduleItem.subtopic'])
            ->where('student_id', $student->id)
            ->whereBetween('tracking_date', [$start, $end])
            ->orderBy('tracking_date', 'desc')
            ->get();
        
        // Günlük istatistikler
        $dailyStats = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dayTrackings = $trackings->where('tracking_date', $date->format('Y-m-d'));
            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'date_formatted' => $date->format('d.m.Y'),
                'day_name' => $date->format('l'),
                'total_lessons' => $dayTrackings->count(),
                'completed_lessons' => $dayTrackings->where('is_completed', true)->count(),
                'total_study_time' => $dayTrackings->sum('study_duration_minutes'),
                'average_understanding' => $dayTrackings->whereNotNull('understanding_score')->avg('understanding_score')
            ];
        }
        
        // Genel istatistikler
        $overallStats = [
            'total_lessons' => $trackings->count(),
            'completed_lessons' => $trackings->where('is_completed', true)->count(),
            'completion_rate' => $trackings->count() > 0 ? round(($trackings->where('is_completed', true)->count() / $trackings->count()) * 100, 1) : 0,
            'total_study_time' => $trackings->sum('study_duration_minutes'),
            'average_understanding' => round($trackings->whereNotNull('understanding_score')->avg('understanding_score') ?? 0, 1),
            'difficulty_breakdown' => $trackings->groupBy('difficulty_level')->map->count(),
            'understanding_breakdown' => $trackings->whereNotNull('understanding_score')->groupBy(function($item) {
                if ($item->understanding_score >= 8) return 'Yüksek (8-10)';
                if ($item->understanding_score >= 6) return 'Orta (6-7)';
                if ($item->understanding_score >= 4) return 'Düşük (4-5)';
                return 'Çok Düşük (1-3)';
            })->map->count()
        ];
        
        return view('admin.daily-reports.student-detail', compact(
            'student', 
            'trackings', 
            'dailyStats', 
            'overallStats',
            'startDate',
            'endDate'
        ));
    }
}