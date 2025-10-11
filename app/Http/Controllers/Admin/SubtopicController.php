<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subtopic;
use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;

class SubtopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kategorileri alt konu sayıları ile birlikte getir
        $categories = Category::with(['courses.topics.subtopics'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('admin.subtopics.index', compact('categories'));
    }

    /**
     * Display subtopics for a specific category.
     */
    public function categorySubtopics(Category $category)
    {
        $subtopics = Subtopic::with(['topic.course'])
            ->whereHas('topic.course', function($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->latest()
            ->paginate(15);
        
        return view('admin.subtopics.category-subtopics', compact('category', 'subtopics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $topics = Topic::with(['course.category'])->where('is_active', true)->get();
        $selectedTopicId = $request->get('topic_id');
        $selectedCategoryId = $request->get('category_id');
        
        // Eğer kategori seçilmişse, sadece o kategoriye ait konuları getir
        if ($selectedCategoryId) {
            $topics = $topics->where('course.category_id', $selectedCategoryId);
        }
        
        return view('admin.subtopics.create', compact('topics', 'selectedTopicId', 'selectedCategoryId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic_id' => 'required|exists:topics,id',
            'order_index' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'content' => 'nullable|string',
        ]);

        Subtopic::create($request->all());

        return redirect()->route('admin.subtopics.index')
            ->with('success', 'Alt konu başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtopic $subtopic)
    {
        $subtopic->load(['topic.course.category']);
        return view('admin.subtopics.show', compact('subtopic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subtopic $subtopic)
    {
        $topics = Topic::with(['course.category'])->where('is_active', true)->get();
        return view('admin.subtopics.edit', compact('subtopic', 'topics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtopic $subtopic)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'topic_id' => 'required|exists:topics,id',
            'order_index' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'content' => 'nullable|string',
        ]);

        $subtopic->update($request->all());

        return redirect()->route('admin.subtopics.index')
            ->with('success', 'Alt konu başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtopic $subtopic)
    {
        $subtopic->delete();

        return redirect()->route('admin.subtopics.index')
            ->with('success', 'Alt konu başarıyla silindi.');
    }
}
