<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleTemplate;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class ScheduleTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = ScheduleTemplate::active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::with('category')->where('is_active', true)->get();
        
        return view('admin.templates.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
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

        $template = ScheduleTemplate::create([
            'name' => $request->name,
            'description' => $request->description,
            'areas' => $request->areas,
            'schedule_items' => $request->schedule_items,
            'is_active' => true,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Program şablonu başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleTemplate $template)
    {
        $template->load(['scheduleItems.course.category', 'scheduleItems.topic', 'scheduleItems.subtopic']);
        
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleTemplate $template)
    {
        $courses = Course::with('category')->where('is_active', true)->get();
        
        return view('admin.templates.edit', compact('template', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScheduleTemplate $template)
    {
        $request->validate([
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

        $template->update([
            'name' => $request->name,
            'description' => $request->description,
            'areas' => $request->areas,
            'schedule_items' => $request->schedule_items,
        ]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Program şablonu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleTemplate $template)
    {
        $template->update(['is_active' => false]);

        return redirect()->route('admin.templates.index')
            ->with('success', 'Program şablonu başarıyla silindi.');
    }

    /**
     * Get template data via AJAX.
     */
    public function getTemplate(Request $request)
    {
        $templateId = $request->get('template_id');
        
        if (!$templateId) {
            return response()->json([
                'success' => false,
                'message' => 'Şablon ID gerekli'
            ], 400);
        }
        
        $template = ScheduleTemplate::find($templateId);
            
        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Şablon bulunamadı'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'template' => [
                'name' => $template->name,
                'areas' => $template->areas,
                'description' => $template->description,
                'schedule_items' => $template->schedule_items
            ]
        ]);
    }

    /**
     * Create template from existing schedule.
     */
    public function createFromSchedule(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:student_schedules,id',
            'template_name' => 'required|string|max:255',
            'template_description' => 'nullable|string',
        ]);

        $schedule = \App\Models\StudentSchedule::with(['scheduleItems.course.category', 'scheduleItems.topic', 'scheduleItems.subtopic'])
            ->find($request->schedule_id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Program bulunamadı'
            ], 404);
        }

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

        $template = ScheduleTemplate::create([
            'name' => $request->template_name,
            'description' => $request->template_description,
            'areas' => $schedule->areas,
            'schedule_items' => $scheduleItems,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Şablon başarıyla oluşturuldu',
            'template_id' => $template->id
        ]);
    }
}
