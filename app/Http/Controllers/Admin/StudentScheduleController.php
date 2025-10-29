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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Redirect to programs page
        return redirect()->route('admin.programs.students');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if ($currentUser->isSuperAdmin()) {
            $students = Student::where('is_active', true)->get();
        } else {
            $students = Student::where('is_active', true)->where('admin_id', $currentUser->id)->get();
        }
        
        $courses = Course::with('category')->where('is_active', true)->get();
        $selectedStudentId = $request->get('student_id');
        $selectedTemplateId = $request->get('template_id');
        
        // Şablonları getir
        $templates = \App\Models\ScheduleTemplate::active()->orderBy('name')->get();
        
        // Aktif kategorileri getir (sadece mevcut kategoriler)
        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.schedules.create', compact('students', 'courses', 'selectedStudentId', 'selectedTemplateId', 'templates', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Aktif kategorilerin isimlerini al
        $activeCategoryNames = \App\Models\Category::where('is_active', true)->pluck('name')->toArray();
        
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'areas' => 'required|array|min:1',
            'areas.*' => 'required|in:' . implode(',', $activeCategoryNames),
            'description' => 'nullable|string',
            'schedule_items' => 'required|array|min:1',
            'schedule_items.*.course_id' => 'required|exists:courses,id',
            'schedule_items.*.topic_id' => 'nullable|exists:topics,id',
            'schedule_items.*.subtopic_id' => 'nullable|exists:subtopics,id',
            'schedule_items.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_items.*.notes' => 'nullable|string',
            // Şablon kaydetme için ek validasyonlar
            'save_as_template' => 'nullable|boolean',
            'template_name' => 'required_if:save_as_template,1|string|max:255',
            'template_description' => 'nullable|string',
        ]);

        // Program oluştur
        $schedule = StudentSchedule::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'areas' => $request->areas,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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

        $successMessage = 'Haftalık program başarıyla oluşturuldu.';

        // Şablon olarak da kaydet
        if ($request->has('save_as_template') && $request->save_as_template) {
            $this->createTemplateFromSchedule($schedule, $request);
            $successMessage .= ' Program ayrıca şablon olarak da kaydedildi.';
        }

        return redirect()->route('admin.schedules.index')
            ->with('success', $successMessage);
    }

    /**
     * Programdan şablon oluştur
     */
    private function createTemplateFromSchedule(StudentSchedule $schedule, Request $request)
    {
        // Schedule items'ları template formatına çevir
        $scheduleItems = $schedule->scheduleItems->map(function ($item) {
            return [
                'day_of_week' => $item->day_of_week,
                'course_id' => $item->course_id,
                'topic_id' => $item->topic_id,
                'subtopic_id' => $item->subtopic_id,
                'notes' => $item->notes
            ];
        })->toArray();

        // Şablon oluştur
        \App\Models\ScheduleTemplate::create([
            'name' => $request->template_name,
            'description' => $request->template_description,
            'areas' => $schedule->areas,
            'schedule_items' => $scheduleItems,
            'is_active' => true,
        ]);
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
            ->orderBy('name')
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
            ->orderBy('name')
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
     * Update schedule items for a student
     */
    public function updateScheduleItems(Request $request, Student $student)
    {
        $request->validate([
            'schedule_items' => 'required|array',
            'schedule_items.*.id' => 'required|exists:schedule_items,id',
            'schedule_items.*.course_id' => 'required|exists:courses,id',
            'schedule_items.*.topic_id' => 'nullable|exists:topics,id',
            'schedule_items.*.subtopic_id' => 'nullable|exists:subtopics,id',
            'schedule_items.*.notes' => 'nullable|string',
            'schedule_items.*.is_completed' => 'boolean',
            'schedule_items.*._delete' => 'boolean',
        ]);

        $scheduleItems = $request->input('schedule_items', []);
        
        foreach ($scheduleItems as $itemData) {
            $scheduleItem = ScheduleItem::find($itemData['id']);
            
            if (!$scheduleItem) {
                continue;
            }
            
            // Eğer silme işareti varsa
            if (isset($itemData['_delete']) && $itemData['_delete']) {
                $scheduleItem->delete();
                continue;
            }
            
            // Güncelle
            $scheduleItem->update([
                'course_id' => $itemData['course_id'],
                'topic_id' => $itemData['topic_id'] ?? null,
                'subtopic_id' => $itemData['subtopic_id'] ?? null,
                'notes' => $itemData['notes'] ?? null,
                'is_completed' => isset($itemData['is_completed']) ? (bool)$itemData['is_completed'] : false,
            ]);
        }

        return redirect()->route('admin.programs.student.calendar', $student)
            ->with('success', 'Program başarıyla güncellendi.');
    }

    /**
     * Create new schedule item for a student
     */
    public function createScheduleItem(Request $request, Student $student)
    {
        $request->validate([
            'day_of_week' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'row_index' => 'required|integer|min:0',
            'course_id' => 'required|exists:courses,id',
            'topic_id' => 'nullable|exists:topics,id',
            'subtopic_id' => 'nullable|exists:subtopics,id',
            'notes' => 'nullable|string',
        ]);

        // Öğrencinin aktif programını bul
        $schedule = $student->schedules()
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Öğrenci için aktif program bulunamadı.'
            ], 400);
        }

        // Yeni schedule item oluştur
        $scheduleItem = ScheduleItem::create([
            'schedule_id' => $schedule->id,
            'course_id' => $request->course_id,
            'topic_id' => $request->topic_id,
            'subtopic_id' => $request->subtopic_id,
            'day_of_week' => $request->day_of_week,
            'notes' => $request->notes,
            'is_completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ders başarıyla eklendi.',
            'schedule_item' => $scheduleItem
        ]);
    }

    /**
     * Get template schedule data via AJAX.
     */
    public function getTemplateSchedule(Request $request)
    {
        try {
            $templateId = $request->get('template_id');
            
            if (!$templateId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şablon ID gerekli'
                ], 400);
            }
            
            $template = \App\Models\ScheduleTemplate::find($templateId);
                
            if (!$template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Şablon bulunamadı'
                ], 404);
            }
            
            // Schedule items'ları formatla - null kontrolü ekle
            $scheduleItems = [];
            if ($template->schedule_items && is_array($template->schedule_items)) {
                $scheduleItems = collect($template->schedule_items)->map(function ($item) {
                    if (!isset($item['course_id'])) {
                        return null;
                    }
                    
                    $course = Course::with('category')->find($item['course_id']);
                    $topic = isset($item['topic_id']) && $item['topic_id'] ? Topic::find($item['topic_id']) : null;
                    $subtopic = isset($item['subtopic_id']) && $item['subtopic_id'] ? Subtopic::find($item['subtopic_id']) : null;
                    
                    return [
                        'day_of_week' => $item['day_of_week'] ?? null,
                        'course_id' => $item['course_id'],
                        'course_name' => $course ? $course->name : '',
                        'course_category' => $course && $course->category ? $course->category->name : '',
                        'topic_id' => $item['topic_id'] ?? null,
                        'topic_name' => $topic ? $topic->name : null,
                        'subtopic_id' => $item['subtopic_id'] ?? null,
                        'subtopic_name' => $subtopic ? $subtopic->name : null,
                        'notes' => $item['notes'] ?? null
                    ];
                })->filter()->values()->all();
            }
            
            return response()->json([
                'success' => true,
                'template' => [
                    'name' => $template->name ?? '',
                    'areas' => $template->areas ?? [],
                    'description' => $template->description ?? '',
                    'schedule_items' => $scheduleItems
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Template load error: ' . $e->getMessage(), [
                'template_id' => $request->get('template_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Şablon yüklenirken bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
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
            ->whereJsonContains('areas', $area)
            ->latest()
            ->paginate(15);
        
        return view('admin.schedules.area-schedules', compact('schedules', 'area'));
    }

    /**
     * Programı olan öğrencileri listele
     */
    public function studentsWithPrograms(Request $request)
    {
        $currentUser = Auth::user();
        $areaFilter = $request->get('area');
        
        $query = Student::with(['schedules' => function($query) use ($areaFilter) {
            $query->where('is_active', true);
            if ($areaFilter) {
                $query->whereJsonContains('areas', $areaFilter);
            }
        }])
        ->whereHas('schedules', function($query) use ($areaFilter) {
            $query->where('is_active', true);
            if ($areaFilter) {
                $query->whereJsonContains('areas', $areaFilter);
            }
        });

        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if (!$currentUser->isSuperAdmin()) {
            $query->where('admin_id', $currentUser->id);
        }

        $students = $query->orderBy('first_name')->get();

        // İstatistikler için öğrencileri al
        $allStudentsQuery = Student::with(['schedules' => function($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('schedules', function($query) {
            $query->where('is_active', true);
        });

        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if (!$currentUser->isSuperAdmin()) {
            $allStudentsQuery->where('admin_id', $currentUser->id);
        }

        $allStudents = $allStudentsQuery->get();

        return view('admin.schedules.students-with-programs', compact('students', 'allStudents', 'areaFilter'));
    }

    /**
     * Öğrencinin programını takvim şeklinde göster
     */
    public function studentCalendar(Student $student)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if (!$currentUser->isSuperAdmin() && $student->admin_id !== $currentUser->id) {
            return redirect()->route('admin.programs.students')
                ->with('error', 'Bu öğrencinin programını görme yetkiniz bulunmamaktadır.');
        }
        
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
                    'area' => $schedule->areas[0] ?? 'TYT', // İlk alanı kullan
                    'day' => $item->day_of_week,
                    'day_name' => $item->day_name,
                    'course' => $item->course,
                    'topic' => $item->topic,
                    'subtopic' => $item->subtopic,
                    'notes' => $item->notes,
                    'is_completed' => $item->is_completed,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date
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

    /**
     * Öğrencinin programını Excel formatında düzenle
     */
    public function studentCalendarEdit(Student $student)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if (!$currentUser->isSuperAdmin() && $student->admin_id !== $currentUser->id) {
            return redirect()->route('admin.programs.students')
                ->with('error', 'Bu öğrencinin programını düzenleme yetkiniz bulunmamaktadır.');
        }
        
        $schedules = $student->schedules()
            ->where('is_active', true)
            ->with(['scheduleItems.course.category', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->get();

        // Tüm dersleri getir
        $courses = Course::with('category')->where('is_active', true)->get();
        
        // Tüm konuları getir
        $topics = Topic::with('course')->where('is_active', true)->get();
        
        // Tüm alt konuları getir
        $subtopics = Subtopic::with('topic')->where('is_active', true)->get();

        // Haftalık programı günlere göre grupla
        $weeklySchedule = collect();
        foreach ($schedules as $schedule) {
            foreach ($schedule->scheduleItems as $item) {
                $weeklySchedule->push([
                    'id' => $item->id,
                    'schedule_id' => $schedule->id,
                    'schedule_name' => $schedule->name,
                    'area' => $schedule->areas[0] ?? 'TYT', // İlk alanı kullan
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

        return view('admin.schedules.student-calendar-edit', compact('student', 'schedules', 'weeklySchedule', 'courses', 'topics', 'subtopics'));
    }

    /**
     * Öğrencinin programını güncelle
     */
    public function studentCalendarUpdate(Request $request, Student $student)
    {
        $request->validate([
            'schedule_items' => 'required|array',
            'schedule_items.*.id' => 'required|exists:schedule_items,id',
            'schedule_items.*.course_id' => 'required|exists:courses,id',
            'schedule_items.*.topic_id' => 'nullable|exists:topics,id',
            'schedule_items.*.subtopic_id' => 'nullable|exists:subtopics,id',
            'schedule_items.*.notes' => 'nullable|string',
            'schedule_items.*.is_completed' => 'boolean',
        ]);

        foreach ($request->schedule_items as $itemData) {
            $scheduleItem = ScheduleItem::find($itemData['id']);
            if ($scheduleItem) {
                $scheduleItem->update([
                    'course_id' => $itemData['course_id'],
                    'topic_id' => $itemData['topic_id'] ?? null,
                    'subtopic_id' => $itemData['subtopic_id'] ?? null,
                    'notes' => $itemData['notes'] ?? null,
                    'is_completed' => $itemData['is_completed'] ?? false,
                ]);
            }
        }

        return redirect()->route('admin.programs.student.calendar', $student)
            ->with('success', 'Program başarıyla güncellendi.');
    }

    /**
     * Öğrencinin programını PDF olarak indir
     */
    public function studentCalendarPdf(Student $student)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if (!$currentUser->isSuperAdmin() && $student->admin_id !== $currentUser->id) {
            return redirect()->route('admin.programs.students')
                ->with('error', 'Bu öğrencinin programını görme yetkiniz bulunmamaktadır.');
        }
        
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
                    'area' => $schedule->areas[0] ?? 'TYT',
                    'day' => $item->day_of_week,
                    'day_name' => $item->day_name,
                    'course' => $item->course,
                    'topic' => $item->topic,
                    'subtopic' => $item->subtopic,
                    'notes' => $item->notes,
                    'is_completed' => $item->is_completed,
                    'start_date' => $schedule->start_date,
                    'end_date' => $schedule->end_date
                ]);
            }
        }

        $weeklySchedule = $weeklySchedule->groupBy('day');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.schedules.student-calendar-pdf', compact('student', 'schedules', 'weeklySchedule'));
        $pdf->setPaper('A4', 'landscape'); // Yatay A4
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('defaultFont', 'DejaVu Sans');
        
        return $pdf->download($student->full_name . '_program_' . date('Y-m-d') . '.pdf');
    }
}
