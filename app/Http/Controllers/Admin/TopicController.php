<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kategorileri konu sayıları ile birlikte getir
        $categories = Category::with(['courses.topics'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.topics.index', compact('categories'));
    }

    /**
     * Display courses for a specific category.
     */
    public function categoryCourses(Category $category)
    {
        $courses = $category->courses()
            ->withCount('topics')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.topics.category-courses', compact('category', 'courses'));
    }

    /**
     * Display topics for a specific course.
     */
    public function courseTopics(Course $course)
    {
        $topics = $course->topics()
            ->withCount('subtopics')
            ->orderBy('order_index')
            ->get();
        
        return view('admin.topics.course-topics', compact('course', 'topics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $courses = Course::with('category')->where('is_active', true)->get();
        $selectedCourseId = $request->get('course_id');
        $selectedCategoryId = $request->get('category_id');
        
        // Eğer kategori seçilmişse, sadece o kategoriye ait dersleri getir
        if ($selectedCategoryId) {
            $courses = $courses->where('category_id', $selectedCategoryId);
        }
        
        return view('admin.topics.create', compact('courses', 'selectedCourseId', 'selectedCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'order_index' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0',
        ]);

        Topic::create($request->all());

        return redirect()->route('admin.topics.index')
            ->with('success', 'Konu başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        $topic->load(['course.category', 'subtopics']);
        return view('admin.topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        $courses = Course::with('category')->where('is_active', true)->get();
        return view('admin.topics.edit', compact('topic', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'order_index' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0',
        ]);

        $topic->update($request->all());

        return redirect()->route('admin.topics.index')
            ->with('success', 'Konu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        if ($topic->subtopics()->count() > 0) {
            return redirect()->route('admin.topics.index')
                ->with('error', 'Bu konuya ait alt konular bulunduğu için silinemez.');
        }

        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('success', 'Konu başarıyla silindi.');
    }
}
