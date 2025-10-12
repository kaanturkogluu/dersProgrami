<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DailyLessonTracking;
use App\Models\ScheduleItem;
use App\Http\Controllers\Student\AuthController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyTrackingController extends Controller
{
    public function __construct()
    {
        // Middleware'i constructor'da değil, route'da tanımlayacağız
    }

    /**
     * Günlük ders takip sayfası
     */
    public function index(Request $request)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        // Tarih parametresi (varsayılan: bugün)
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedDate = Carbon::parse($date);
        
        // Öğrencinin aktif programlarını getir
        $schedules = $student->schedules()
            ->where('is_active', true)
            ->with(['scheduleItems.course', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->get();

        // Seçilen günün derslerini getir
        $dayLessons = collect();
        foreach ($schedules as $schedule) {
            foreach ($schedule->scheduleItems as $item) {
                if ($item->day_of_week === strtolower($selectedDate->format('l'))) {
                    $tracking = DailyLessonTracking::where('student_id', $student->id)
                        ->where('schedule_item_id', $item->id)
                        ->where('tracking_date', $selectedDate)
                        ->first();
                    
                    $dayLessons->push([
                        'id' => $item->id,
                        'schedule_name' => $schedule->name,
                        'course' => $item->course,
                        'topic' => $item->topic,
                        'subtopic' => $item->subtopic,
                        'notes' => $item->notes,
                        'tracking' => $tracking
                    ]);
                }
            }
        }

        return view('student.daily-tracking', compact('student', 'dayLessons', 'selectedDate'));
    }

    /**
     * Ders takip kaydetme
     */
    public function store(Request $request)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return response()->json(['error' => 'Öğrenci oturumu bulunamadı'], 401);
        }

        $request->validate([
            'schedule_item_id' => 'required|exists:schedule_items,id',
            'tracking_date' => 'required|date',
            'is_completed' => 'boolean',
            'study_duration_minutes' => 'nullable|integer|min:0|max:480', // Max 8 saat
            'notes' => 'nullable|string|max:1000',
            'difficulty_level' => 'nullable|in:kolay,orta,zor',
            'understanding_score' => 'nullable|integer|min:1|max:10'
        ]);

        // Mevcut kaydı bul veya yeni oluştur
        $tracking = DailyLessonTracking::updateOrCreate(
            [
                'student_id' => $student->id,
                'schedule_item_id' => $request->schedule_item_id,
                'tracking_date' => $request->tracking_date
            ],
            [
                'is_completed' => $request->boolean('is_completed'),
                'study_duration_minutes' => $request->study_duration_minutes,
                'notes' => $request->notes,
                'difficulty_level' => $request->difficulty_level,
                'understanding_score' => $request->understanding_score
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Ders takibi başarıyla kaydedildi.',
            'tracking' => $tracking
        ]);
    }

    /**
     * Ders takip güncelleme
     */
    public function update(Request $request, DailyLessonTracking $tracking)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student || $tracking->student_id !== $student->id) {
            return response()->json(['error' => 'Yetkisiz erişim'], 403);
        }

        $request->validate([
            'is_completed' => 'boolean',
            'study_duration_minutes' => 'nullable|integer|min:0|max:480',
            'notes' => 'nullable|string|max:1000',
            'difficulty_level' => 'nullable|in:kolay,orta,zor',
            'understanding_score' => 'nullable|integer|min:1|max:10'
        ]);

        $tracking->update($request->only([
            'is_completed',
            'study_duration_minutes',
            'notes',
            'difficulty_level',
            'understanding_score'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Ders takibi başarıyla güncellendi.',
            'tracking' => $tracking
        ]);
    }

    /**
     * Ders takip silme
     */
    public function destroy(DailyLessonTracking $tracking)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student || $tracking->student_id !== $student->id) {
            return response()->json(['error' => 'Yetkisiz erişim'], 403);
        }

        $tracking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ders takibi başarıyla silindi.'
        ]);
    }
}