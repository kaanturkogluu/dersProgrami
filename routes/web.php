<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\SubtopicController;
use App\Http\Controllers\Admin\StudentScheduleController;
use App\Http\Controllers\Admin\ScheduleTemplateController;
use App\Http\Controllers\Admin\TopicTrackingController;
use App\Http\Controllers\Admin\QuestionAnalysisController;

Route::get('/', function () {
    // Eğer admin giriş yapmışsa dashboard'a, yoksa login'e yönlendir
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Giriş sayfaları (middleware olmadan)
    Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
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
    // Özel route'ları resource route'undan ÖNCE tanımla (çünkü Laravel route matching'de önce eşleşen route'u alır)
    Route::get('schedules/template', [StudentScheduleController::class, 'getTemplateSchedule'])->name('schedules.template');
    Route::get('schedules/courses/by-area', [StudentScheduleController::class, 'getCoursesByArea'])->name('schedules.courses.by-area');
    Route::get('schedules/topics/by-course', [StudentScheduleController::class, 'getTopicsByCourse'])->name('schedules.topics.by-course');
    Route::get('schedules/subtopics/by-topic', [StudentScheduleController::class, 'getSubtopicsByTopic'])->name('schedules.subtopics.by-topic');
    Route::get('schedules/area/{area}', [StudentScheduleController::class, 'areaSchedules'])->name('schedules.area');
    Route::get('students/{student}/schedules', [StudentScheduleController::class, 'studentSchedules'])->name('schedules.student');
    Route::resource('schedules', StudentScheduleController::class);
    
    // Schedule Templates
    Route::resource('templates', ScheduleTemplateController::class);
    Route::get('templates/template/data', [ScheduleTemplateController::class, 'getTemplate'])->name('templates.template.data');
    Route::post('templates/create-from-schedule', [ScheduleTemplateController::class, 'createFromSchedule'])->name('templates.create-from-schedule');
    
    // Schedule Items
    Route::delete('schedule-items/{scheduleItem}', [StudentScheduleController::class, 'destroyScheduleItem'])->name('schedule-items.destroy');
    
    // Student Programs Overview
    Route::get('programs/students', [StudentScheduleController::class, 'studentsWithPrograms'])->name('programs.students');
    Route::get('programs/student/{student}/calendar', [StudentScheduleController::class, 'studentCalendar'])->name('programs.student.calendar');
    Route::get('programs/student/{student}/calendar/pdf', [StudentScheduleController::class, 'studentCalendarPdf'])->name('programs.student.calendar.pdf');
    Route::get('programs/student/{student}/calendar/edit', [StudentScheduleController::class, 'studentCalendarEdit'])->name('programs.student.calendar.edit');
    Route::put('programs/student/{student}/schedule-items', [StudentScheduleController::class, 'updateScheduleItems'])->name('programs.student.schedule-items.update');
    Route::post('programs/student/{student}/schedule-items', [StudentScheduleController::class, 'createScheduleItem'])->name('programs.student.schedule-items.create');
    Route::post('programs/student/{student}/calendar/update', [StudentScheduleController::class, 'studentCalendarUpdate'])->name('programs.student.calendar.update');
    Route::post('programs/student/{student}/schedule-items/update-day', [StudentScheduleController::class, 'updateScheduleItemDay'])->name('programs.student.schedule-items.update-day');
    
    // Daily Reports
    Route::get('daily-reports', [\App\Http\Controllers\Admin\DailyReportsController::class, 'index'])->name('daily-reports.index');
    Route::get('daily-reports/student/{student}', [\App\Http\Controllers\Admin\DailyReportsController::class, 'studentDetail'])->name('daily-reports.student');
    
    // Admin Management
    Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);
    
    // Mail Management
    Route::get('mail', [\App\Http\Controllers\Admin\MailController::class, 'index'])->name('mail.index');
    Route::post('mail/send-welcome', [\App\Http\Controllers\Admin\MailController::class, 'sendWelcome'])->name('mail.send-welcome');
    Route::post('mail/send-daily-reminder', [\App\Http\Controllers\Admin\MailController::class, 'sendDailyReminder'])->name('mail.send-daily-reminder');
    Route::post('mail/send-daily-reminder-all', [\App\Http\Controllers\Admin\MailController::class, 'sendDailyReminderToAll'])->name('mail.send-daily-reminder-all');
    Route::post('mail/send-test', [\App\Http\Controllers\Admin\MailController::class, 'sendTestMail'])->name('mail.send-test');
    Route::post('mail/test-connection', [\App\Http\Controllers\Admin\MailController::class, 'testConnection'])->name('mail.test-connection');
    
    // Topic Tracking
    Route::get('topic-tracking/student-progress', [TopicTrackingController::class, 'studentProgress'])->name('topic-tracking.student-progress');
    Route::get('topic-tracking/student-progress/{student}', [TopicTrackingController::class, 'getStudentProgress'])->name('topic-tracking.student-progress.data');
    Route::post('topic-tracking/toggle-status', [TopicTrackingController::class, 'toggleTopicStatus'])->name('topic-tracking.toggle-status');
    Route::resource('topic-tracking', TopicTrackingController::class);
    Route::post('topic-tracking/{topicTracking}/update-status', [TopicTrackingController::class, 'updateStatus'])->name('topic-tracking.update-status');
    Route::get('topic-tracking/subtopics/by-topic', [TopicTrackingController::class, 'getSubtopics'])->name('topic-tracking.subtopics.by-topic');
    
    // Question Analysis
    Route::resource('question-analysis', QuestionAnalysisController::class);
    Route::get('question-analysis/student/{student}/stats', [QuestionAnalysisController::class, 'studentStats'])->name('question-analysis.student.stats');
    Route::get('question-analysis/student/{student}/detailed', [QuestionAnalysisController::class, 'studentDetailed'])->name('question-analysis.student.detailed');
    Route::get('question-analysis/subtopics/by-topic', [QuestionAnalysisController::class, 'getSubtopics'])->name('question-analysis.subtopics.by-topic');
});

// Student Routes
Route::prefix('student')->name('student.')->group(function () {
    // Authentication
    Route::get('login', [\App\Http\Controllers\Student\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Student\AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [\App\Http\Controllers\Student\AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('previous-lessons', [\App\Http\Controllers\Student\DashboardController::class, 'previousLessons'])->name('previous-lessons');
    
    // Daily Tracking
    Route::get('daily-tracking', [\App\Http\Controllers\Student\DailyTrackingController::class, 'index'])->name('daily-tracking');
    Route::post('daily-tracking', [\App\Http\Controllers\Student\DailyTrackingController::class, 'store'])->name('daily-tracking.store');
    Route::put('daily-tracking/{tracking}', [\App\Http\Controllers\Student\DailyTrackingController::class, 'update'])->name('daily-tracking.update');
    Route::delete('daily-tracking/{tracking}', [\App\Http\Controllers\Student\DailyTrackingController::class, 'destroy'])->name('daily-tracking.destroy');
    
    // Question Tracking
    Route::get('question-tracking', [\App\Http\Controllers\Student\QuestionTrackingController::class, 'index'])->name('question-tracking');
    Route::post('question-tracking', [\App\Http\Controllers\Student\QuestionTrackingController::class, 'store'])->name('question-tracking.store');
    Route::delete('question-tracking/{id}', [\App\Http\Controllers\Student\QuestionTrackingController::class, 'destroy'])->name('question-tracking.destroy');
});
