<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Topic;
use App\Models\TopicTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with(['topicTrackings.topic', 'topicTrackings.subtopic'])
            ->where('admin_id', Auth::id())
            ->get();

        return view('admin.topic-tracking.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('admin_id', Auth::id())->get();
        $topics = Topic::with(['course.category', 'subtopics'])->get();
        $selectedStudentId = $request->get('student_id');
        
        return view('admin.topic-tracking.create', compact('students', 'topics', 'selectedStudentId'));
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
            'difficulty_level' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string'
        ]);

        TopicTracking::create([
            'student_id' => $request->student_id,
            'topic_id' => $request->topic_id,
            'subtopic_id' => $request->subtopic_id,
            'difficulty_level' => $request->difficulty_level,
            'notes' => $request->notes,
            'status' => 'not_started'
        ]);

        return redirect()->route('admin.topic-tracking.index')
            ->with('success', 'Konu takip kaydı başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TopicTracking $topicTracking)
    {
        $topicTracking->load(['student', 'topic.course.category', 'subtopic', 'approvedBy']);
        
        return view('admin.topic-tracking.show', compact('topicTracking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TopicTracking $topicTracking)
    {
        $students = Student::where('admin_id', Auth::id())->get();
        $topics = Topic::with(['course.category', 'subtopics'])->get();
        
        return view('admin.topic-tracking.edit', compact('topicTracking', 'students', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TopicTracking $topicTracking)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'topic_id' => 'required|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'status' => 'required|in:not_started,in_progress,completed,approved',
            'difficulty_level' => 'required|integer|min:1|max:5',
            'time_spent_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        $topicTracking->update($request->all());

        return redirect()->route('admin.topic-tracking.index')
            ->with('success', 'Konu takip kaydı başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TopicTracking $topicTracking)
    {
        $topicTracking->delete();

        return redirect()->route('admin.topic-tracking.index')
            ->with('success', 'Konu takip kaydı başarıyla silindi.');
    }

    /**
     * Update topic status
     */
    public function updateStatus(Request $request, TopicTracking $topicTracking)
    {
        $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,approved'
        ]);

        $status = $request->status;

        switch ($status) {
            case 'in_progress':
                $topicTracking->markAsStarted();
                break;
            case 'completed':
                $topicTracking->markAsCompleted();
                break;
            case 'approved':
                $topicTracking->markAsApproved(Auth::id());
                break;
            default:
                $topicTracking->update(['status' => $status]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Durum başarıyla güncellendi.',
            'status' => $topicTracking->status
        ]);
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
