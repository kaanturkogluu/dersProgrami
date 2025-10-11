<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\SubtopicController;
use App\Http\Controllers\Admin\StudentScheduleController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Students
    Route::resource('students', StudentController::class);
    
    // Courses
    Route::resource('courses', CourseController::class);
    
    // Topics
    Route::resource('topics', TopicController::class);
    Route::get('categories/{category}/courses', [TopicController::class, 'categoryCourses'])->name('topics.category');
    Route::get('courses/{course}/topics', [TopicController::class, 'courseTopics'])->name('topics.course');
    
    // Subtopics
    Route::resource('subtopics', SubtopicController::class);
    Route::get('categories/{category}/subtopics', [SubtopicController::class, 'categorySubtopics'])->name('subtopics.category');
    
    // Student Schedules
    Route::resource('schedules', StudentScheduleController::class);
    Route::get('students/{student}/schedules', [StudentScheduleController::class, 'studentSchedules'])->name('schedules.student');
    Route::get('schedules/area/{area}', [StudentScheduleController::class, 'areaSchedules'])->name('schedules.area');
    Route::get('schedules/courses/by-area', [StudentScheduleController::class, 'getCoursesByArea'])->name('schedules.courses.by-area');
    Route::get('schedules/topics/by-course', [StudentScheduleController::class, 'getTopicsByCourse'])->name('schedules.topics.by-course');
    Route::get('schedules/subtopics/by-topic', [StudentScheduleController::class, 'getSubtopicsByTopic'])->name('schedules.subtopics.by-topic');
    
    // Student Programs Overview
    Route::get('programs/students', [StudentScheduleController::class, 'studentsWithPrograms'])->name('programs.students');
    Route::get('programs/student/{student}/calendar', [StudentScheduleController::class, 'studentCalendar'])->name('programs.student.calendar');
});
