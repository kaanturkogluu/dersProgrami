<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnalysis;
use App\Models\Student;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionAnalyses = QuestionAnalysis::with(['student', 'topic.course.category', 'subtopic'])
            ->whereHas('student', function($query) {
                $query->where('admin_id', Auth::id());
            })
            ->orderBy('solved_at', 'desc')
            ->paginate(20);

        return view('admin.question-analysis.index', compact('questionAnalyses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('admin_id', Auth::id())->get();
        $topics = Topic::with(['course.category', 'subtopics'])->get();
        $selectedStudentId = $request->get('student_id');
        $selectedTopicId = $request->get('topic_id');
        $selectedSubtopicId = $request->get('subtopic_id');
        
        return view('admin.question-analysis.create', compact('students', 'topics', 'selectedStudentId', 'selectedTopicId', 'selectedSubtopicId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'topic_id' => 'required|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'question_source' => 'nullable|string|max:255',
            'question_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'difficulty' => 'required|in:kolay,orta,zor',
            'total_questions' => 'required|integer|min:1|max:1000',
            'correct_count' => 'required|integer|min:0|max:1000',
            'incorrect_count' => 'required|integer|min:0|max:1000',
            'empty_count' => 'required|integer|min:0|max:1000',
            'solved_at' => 'required|date'
        ]);

        // Toplam kontrolü
        $total = $request->total_questions;
        $correct = $request->correct_count;
        $incorrect = $request->incorrect_count;
        $empty = $request->empty_count;

        if ($correct + $incorrect + $empty > $total) {
            return back()->withErrors(['total_questions' => 'Doğru + Yanlış + Boş sayısı toplam soru sayısını geçemez.'])->withInput();
        }

        // Tek kayıt oluştur - toplu soru analizi
        QuestionAnalysis::create([
            'student_id' => $request->student_id,
            'topic_id' => $request->topic_id,
            'subtopic_id' => $request->subtopic_id,
            'question_source' => $request->question_source,
            'question_year' => $request->question_year,
            'question_number' => $total, // Toplam soru sayısını buraya kaydet
            'difficulty' => $request->difficulty,
            'result' => 'correct', // Ana sonuç olarak doğru kabul et (çünkü doğru sayısı ayrı tutuluyor)
            'student_answer' => $correct, // Doğru sayısını buraya kaydet
            'correct_answer' => $total, // Toplam soru sayısını buraya kaydet
            'explanation' => $incorrect, // Yanlış sayısını buraya kaydet
            'notes' => $empty, // Boş sayısını buraya kaydet
            'solved_at' => $request->solved_at
        ]);

        return redirect()->route('admin.question-analysis.index')
            ->with('success', $total . ' soruluk analiz başarıyla kaydedildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionAnalysis $questionAnalysis)
    {
        $questionAnalysis->load(['student', 'topic.course.category', 'subtopic']);
        
        return view('admin.question-analysis.show', compact('questionAnalysis'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestionAnalysis $questionAnalysis)
    {
        $students = Student::where('admin_id', Auth::id())->get();
        $topics = Topic::with(['course.category', 'subtopics'])->get();
        
        return view('admin.question-analysis.edit', compact('questionAnalysis', 'students', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionAnalysis $questionAnalysis)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'topic_id' => 'required|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'question_source' => 'nullable|string|max:255',
            'question_year' => 'nullable|integer|min:2000|max:' . date('Y'),
            'difficulty' => 'required|in:kolay,orta,zor',
            'result' => 'required|in:correct,incorrect,empty',
            'solved_at' => 'required|date'
        ]);

        $questionAnalysis->update($request->all());

        return redirect()->route('admin.question-analysis.index')
            ->with('success', 'Soru analizi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionAnalysis $questionAnalysis)
    {
        $questionAnalysis->delete();

        return redirect()->route('admin.question-analysis.index')
            ->with('success', 'Soru analizi başarıyla silindi.');
    }

    /**
     * Get statistics for a student
     */
    public function studentStats(Student $student)
    {
        $questionAnalyses = $student->questionAnalyses()->get();
        $totalQuestions = $questionAnalyses->sum(function($q) { return $q->getTotalQuestions(); });
        $correctAnswers = $questionAnalyses->sum(function($q) { return $q->getCorrectCount(); });
        $incorrectAnswers = $questionAnalyses->sum(function($q) { return $q->getIncorrectCount(); });
        $emptyAnswers = $questionAnalyses->sum(function($q) { return $q->getEmptyCount(); });
        
        // Net hesaplama (3 yanlış 1 doğruyu götürür)
        $net = \App\Models\QuestionAnalysis::calculateNet($correctAnswers, $incorrectAnswers);
        
        // Yıldızlama sistemi
        $starRating = \App\Models\QuestionAnalysis::getStarRating($correctAnswers, $totalQuestions);
        $starColor = \App\Models\QuestionAnalysis::getStarColor($starRating);
        
        $stats = [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'empty_answers' => $emptyAnswers,
            'net' => round($net, 2),
            'star_rating' => $starRating,
            'star_color' => $starColor,
            'success_rate' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0,
            'average_time' => $student->questionAnalyses()->avg('time_spent_seconds'),
            'by_difficulty' => $student->questionAnalyses()
                ->selectRaw('difficulty, COUNT(*) as count')
                ->groupBy('difficulty')
                ->pluck('count', 'difficulty'),
            'by_topic' => $student->questionAnalyses()
                ->with('topic')
                ->selectRaw('topic_id, COUNT(*) as count')
                ->groupBy('topic_id')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->topic->name => $item->count];
                })
        ];

        return response()->json($stats);
    }

    /**
     * Get detailed statistics for a student
     */
    public function studentDetailed(Student $student)
    {
        $questionAnalyses = $student->questionAnalyses()
            ->with(['topic.course.category', 'subtopic'])
            ->orderBy('solved_at', 'desc')
            ->paginate(20);

        $allAnalyses = $student->questionAnalyses()->get();
        $totalQuestions = $allAnalyses->sum(function($q) { return $q->getTotalQuestions(); });
        $correctAnswers = $allAnalyses->sum(function($q) { return $q->getCorrectCount(); });
        $incorrectAnswers = $allAnalyses->sum(function($q) { return $q->getIncorrectCount(); });
        $emptyAnswers = $allAnalyses->sum(function($q) { return $q->getEmptyCount(); });
        
        $net = \App\Models\QuestionAnalysis::calculateNet($correctAnswers, $incorrectAnswers);
        $starRating = \App\Models\QuestionAnalysis::getStarRating($correctAnswers, $totalQuestions);
        $starColor = \App\Models\QuestionAnalysis::getStarColor($starRating);
        
        $stats = [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'empty_answers' => $emptyAnswers,
            'net' => round($net, 2),
            'star_rating' => $starRating,
            'star_color' => $starColor,
            'success_rate' => $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 1) : 0,
            'average_time' => $student->questionAnalyses()->avg('time_spent_seconds'),
        ];

        return view('admin.question-analysis.student-detailed', compact('student', 'questionAnalyses', 'stats'));
    }

    /**
     * Get subtopics for a topic
     */
    public function getSubtopics(Request $request)
    {
        $topicId = $request->get('topic_id');
        $subtopics = \App\Models\Subtopic::where('topic_id', $topicId)->get();
        
        return response()->json($subtopics);
    }
}
