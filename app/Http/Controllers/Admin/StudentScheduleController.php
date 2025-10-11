<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentSchedule;
use App\Models\ScheduleItem;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class StudentScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = StudentSchedule::with(['student', 'scheduleItems.course', 'scheduleItems.topic'])
            ->latest()
            ->paginate(15);
        
        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $students = Student::where('is_active', true)->get();
        $courses = Course::with('category')->where('is_active', true)->get();
        $selectedStudentId = $request->get('student_id');
        
        return view('admin.schedules.create', compact('students', 'courses', 'selectedStudentId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'areas' => 'required|array|min:1',
            'areas.*' => 'required|in:TYT,EA,SAY,SOZ,DIL,KPSS',
            'description' => 'nullable|string',
            'schedule_items' => 'required|array|min:1',
            'schedule_items.*.course_id' => 'required|exists:courses,id',
            'schedule_items.*.topic_id' => 'nullable|exists:topics,id',
            'schedule_items.*.subtopic_id' => 'nullable|exists:subtopics,id',
            'schedule_items.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_items.*.notes' => 'nullable|string',
        ]);

        // Program oluştur
        $schedule = StudentSchedule::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'areas' => $request->areas,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addMonths(3)->format('Y-m-d'),
            'description' => $request->description,
            'is_active' => true,
        ]);

        // Program öğelerini oluştur
        foreach ($request->schedule_items as $item) {
            $schedule->scheduleItems()->create([
                'course_id' => $item['course_id'],
                'topic_id' => $item['topic_id'] ?? null,
                'subtopic_id' => $item['subtopic_id'] ?? null,
                'day_of_week' => $item['day_of_week'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Haftalık program başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentSchedule $schedule)
    {
        $schedule->load(['student', 'scheduleItems.course.category', 'scheduleItems.topic', 'scheduleItems.subtopic']);
        
        // Haftalık programı günlere göre grupla
        $weeklySchedule = $schedule->scheduleItems->groupBy('day_of_week');
        
        return view('admin.schedules.show', compact('schedule', 'weeklySchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentSchedule $schedule)
    {
        $students = Student::where('is_active', true)->get();
        $courses = Course::with('category')->where('is_active', true)->get();
        $schedule->load('scheduleItems');
        
        return view('admin.schedules.edit', compact('schedule', 'students', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentSchedule $schedule)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'areas' => 'required|array|min:1',
            'areas.*' => 'required|in:TYT,EA,SAY,SOZ,DIL,KPSS',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $schedule->update([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'areas' => $request->areas,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Program başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Program başarıyla silindi.');
    }

    /**
     * Öğrenciye ait programları getir
     */
    public function studentSchedules(Student $student)
    {
        $schedules = $student->schedules()
            ->with('scheduleItems.course')
            ->latest()
            ->get();
        
        return view('admin.schedules.student-schedules', compact('student', 'schedules'));
    }

    /**
     * Get topics by course via AJAX.
     */
    public function getTopicsByCourse(Request $request)
    {
        $courseId = $request->get('course_id');
        
        if (!$courseId) {
            return response()->json(['topics' => []]);
        }
        
        $topics = Topic::where('course_id', $courseId)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();
        
        return response()->json([
            'topics' => $topics->map(function($topic) {
                return [
                    'id' => $topic->id,
                    'name' => $topic->name,
                    'duration_minutes' => $topic->duration_minutes
                ];
            })
        ]);
    }

    /**
     * Get subtopics by topic via AJAX.
     */
    public function getSubtopicsByTopic(Request $request)
    {
        $topicId = $request->get('topic_id');
        
        if (!$topicId) {
            return response()->json(['subtopics' => []]);
        }
        
        $subtopics = Subtopic::where('topic_id', $topicId)
            ->where('is_active', true)
            ->orderBy('order_index')
            ->get();
        
        return response()->json([
            'subtopics' => $subtopics->map(function($subtopic) {
                return [
                    'id' => $subtopic->id,
                    'name' => $subtopic->name,
                    'duration_minutes' => $subtopic->duration_minutes
                ];
            })
        ]);
    }

    /**
     * Get courses by areas via AJAX.
     */
    public function getCoursesByArea(Request $request)
    {
        $areasParam = $request->get('areas');
        
        if (!$areasParam) {
            return response()->json(['courses' => []]);
        }
        
        // Virgülle ayrılmış alanları array'e çevir
        $areas = explode(',', $areasParam);
        
        $courses = Course::with('category')
            ->whereHas('category', function($query) use ($areas) {
                $query->whereIn('name', $areas);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'courses' => $courses->map(function($course) {
                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'category' => $course->category->name
                ];
            })
        ]);
    }

    /**
     * Belirli bir alan için programları getir
     */
    public function areaSchedules($area)
    {
        $schedules = StudentSchedule::with(['student', 'scheduleItems.course'])
            ->where('area', $area)
            ->latest()
            ->paginate(15);
        
        return view('admin.schedules.area-schedules', compact('schedules', 'area'));
    }

    /**
     * Programı olan öğrencileri listele
     */
    public function studentsWithPrograms()
    {
        $students = Student::with(['schedules' => function($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('schedules', function($query) {
            $query->where('is_active', true);
        })
        ->orderBy('first_name')
        ->get();

        return view('admin.schedules.students-with-programs', compact('students'));
    }

    /**
     * Öğrencinin programını takvim şeklinde göster
     */
    public function studentCalendar(Student $student)
    {
        $schedules = $student->schedules()
            ->where('is_active', true)
            ->with(['scheduleItems.course.category', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->get();

        // Haftalık programı günlere göre grupla
        $weeklySchedule = collect();
        foreach ($schedules as $schedule) {
            foreach ($schedule->scheduleItems as $item) {
                $weeklySchedule->push([
                    'schedule_name' => $schedule->name,
                    'area' => $schedule->area,
                    'day' => $item->day_of_week,
                    'day_name' => $item->day_name,
                    'course' => $item->course,
                    'topic' => $item->topic,
                    'subtopic' => $item->subtopic,
                    'notes' => $item->notes,
                    'is_completed' => $item->is_completed
                ]);
            }
        }

        $weeklySchedule = $weeklySchedule->groupBy('day');

        return view('admin.schedules.student-calendar', compact('student', 'schedules', 'weeklySchedule'));
    }

    /**
     * Schedule item'ı sil
     */
    public function destroyScheduleItem(ScheduleItem $scheduleItem)
    {
        $scheduleItem->delete();
        
        return redirect()->back()
            ->with('success', 'Program öğesi başarıyla silindi.');
    }
}
