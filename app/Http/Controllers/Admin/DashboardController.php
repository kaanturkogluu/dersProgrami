<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Student;
use App\Models\Course;
use App\Models\Topic;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => Category::count(),
            'students' => Student::count(),
            'courses' => Course::count(),
            'topics' => Topic::count(),
            'subtopics' => Subtopic::count(),
        ];

        $recentStudents = Student::latest()->take(5)->get();
        $recentCourses = Course::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentStudents', 'recentCourses'));
    }

    /**
     * Admin çıkış işlemi
     */
    public function logout()
    {
        // Admin oturumunu temizle (eğer varsa)
        session()->flush();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin oturumu başarıyla sonlandırıldı.');
    }
}
