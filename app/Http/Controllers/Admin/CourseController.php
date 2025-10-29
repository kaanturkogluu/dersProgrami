<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('category')->withCount('topics')->latest()->get();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'duration_hours' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Course::create($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Ders başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['category', 'topics.subtopics']);
        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'duration_hours' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $course->update($request->all());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Ders başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        if ($course->topics()->count() > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Bu derse ait konular bulunduğu için silinemez.');
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Ders başarıyla silindi.');
    }
}
