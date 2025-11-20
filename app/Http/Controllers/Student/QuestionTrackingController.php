<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\QuestionAnalysis;
use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuestionTrackingController extends Controller
{
    /**
     * Soru takip sayfası
     */
    public function index(Request $request)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        // Tarih filtresi (varsayılan: son 7 gün)
        $days = $request->get('days', 7);
        $startDate = Carbon::today()->subDays($days);
        $endDate = Carbon::today();

        // Öğrencinin soru kayıtlarını getir
        $questions = QuestionAnalysis::where('student_id', $student->id)
            ->whereBetween('solved_at', [$startDate, $endDate])
            ->with(['topic.course', 'subtopic'])
            ->orderBy('solved_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // İstatistikler
        $stats = [
            'total_questions' => $questions->sum('question_number'),
            'total_correct' => $questions->sum(function($q) {
                return (int) $q->student_answer;
            }),
            'total_incorrect' => $questions->sum(function($q) {
                return (int) $q->explanation;
            }),
            'total_empty' => $questions->sum(function($q) {
                return (int) $q->notes;
            }),
            'net' => 0
        ];

        // Net hesapla (doğru - (yanlış / 3))
        $stats['net'] = round($stats['total_correct'] - ($stats['total_incorrect'] / 3), 2);

        // Ders bazlı istatistikler
        $courseStats = [];
        foreach ($questions as $question) {
            if ($question->topic && $question->topic->course) {
                $courseId = $question->topic->course->id;
                $courseName = $question->topic->course->name;
                
                if (!isset($courseStats[$courseId])) {
                    $courseStats[$courseId] = [
                        'name' => $courseName,
                        'total' => 0,
                        'correct' => 0,
                        'incorrect' => 0,
                        'empty' => 0,
                        'net' => 0
                    ];
                }
                
                $courseStats[$courseId]['total'] += (int) $question->question_number;
                $courseStats[$courseId]['correct'] += (int) $question->student_answer;
                $courseStats[$courseId]['incorrect'] += (int) $question->explanation;
                $courseStats[$courseId]['empty'] += (int) $question->notes;
                $courseStats[$courseId]['net'] = round(
                    $courseStats[$courseId]['correct'] - ($courseStats[$courseId]['incorrect'] / 3),
                    2
                );
            }
        }

        // Dersler listesi (form için)
        $courses = Course::orderBy('name')->get();

        return view('student.question-tracking', compact(
            'student', 
            'questions', 
            'stats', 
            'courseStats',
            'courses',
            'days', 
            'startDate', 
            'endDate'
        ));
    }

    /**
     * Yeni soru kaydı oluştur
     */
    public function store(Request $request)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'total_questions' => 'required|integer|min:1',
            'correct_count' => 'required|integer|min:0',
            'incorrect_count' => 'required|integer|min:0',
        ]);

        // Boş sayısını hesapla
        $totalQuestions = (int) $request->total_questions;
        $correctCount = (int) $request->correct_count;
        $incorrectCount = (int) $request->incorrect_count;

        // Mantık kontrolleri
        if ($correctCount > $totalQuestions) {
            return back()->with('error', "Doğru sayısı ({$correctCount}), toplam soru sayısından ({$totalQuestions}) fazla olamaz!")
                ->withInput();
        }

        if ($incorrectCount > $totalQuestions) {
            return back()->with('error', "Yanlış sayısı ({$incorrectCount}), toplam soru sayısından ({$totalQuestions}) fazla olamaz!")
                ->withInput();
        }

        if ($correctCount + $incorrectCount > $totalQuestions) {
            return back()->with('error', "Doğru ({$correctCount}) + Yanlış ({$incorrectCount}) = " . ($correctCount + $incorrectCount) . " - Bu toplam, toplam soru sayısından ({$totalQuestions}) fazla olamaz!")
                ->withInput();
        }

        $emptyCount = $totalQuestions - $correctCount - $incorrectCount;

        // Seçilen ders için ilk topic'i al
        $topic = Topic::where('course_id', $request->course_id)
            ->orderBy('order_index')
            ->first();
        
        if (!$topic) {
            return back()->with('error', 'Bu ders için konu bulunamadı!')
                ->withInput();
        }
        
        // QuestionAnalysis modelini kullanarak kaydet
        // Bu modelde alanlar farklı amaçlarla kullanılıyor:
        // - question_number: toplam soru sayısı
        // - student_answer: doğru sayısı
        // - explanation: yanlış sayısı
        // - notes: boş sayısı
        // - topic_id: ders bilgisi için kullanılıyor (course üzerinden erişim)
        QuestionAnalysis::create([
            'student_id' => $student->id,
            'topic_id' => $topic->id,
            'subtopic_id' => null,
            'question_source' => $topic->course->name,
            'question_number' => $totalQuestions,
            'student_answer' => $correctCount,
            'explanation' => $incorrectCount,
            'notes' => $emptyCount,
            'solved_at' => now()->toDateString(),
            'result' => $correctCount > $incorrectCount ? 'correct' : ($correctCount == 0 ? 'empty' : 'incorrect'),
            'difficulty' => 'orta'
        ]);

        return redirect()->route('student.question-tracking')
            ->with('success', 'Soru kaydı başarıyla eklendi!');
    }

    /**
     * Soru kaydını sil
     */
    public function destroy($id)
    {
        $student = AuthController::getCurrentStudent();
        
        if (!$student) {
            return redirect()->route('student.login')
                ->with('error', 'Lütfen önce giriş yapın.');
        }

        $question = QuestionAnalysis::where('student_id', $student->id)
            ->findOrFail($id);

        $question->delete();

        return redirect()->route('student.question-tracking')
            ->with('success', 'Soru kaydı başarıyla silindi!');
    }
}

