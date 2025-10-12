<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Öğrenci giriş sayfası
     */
    public function showLoginForm()
    {
        return view('student.auth.login');
    }

    /**
     * Öğrenci giriş işlemi
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::where('student_number', $request->student_number)
            ->where('is_active', true)
            ->first();

        if ($student && Hash::check($request->password, $student->password)) {
            // Öğrenci oturumunu başlat
            session(['student_id' => $student->id, 'student_name' => $student->full_name]);
            
            return redirect()->route('student.dashboard')
                ->with('success', 'Hoş geldiniz, ' . $student->first_name . '!');
        }

        return back()->withErrors([
            'student_number' => 'Öğrenci numarası veya şifre hatalı.',
        ])->withInput($request->only('student_number'));
    }

    /**
     * Öğrenci çıkış işlemi
     */
    public function logout()
    {
        $studentName = session('student_name', 'Öğrenci');
        session()->forget(['student_id', 'student_name']);
        
        return redirect()->route('student.login')
            ->with('success', 'Hoşça kalın, ' . $studentName . '! Başarıyla çıkış yaptınız.');
    }

    /**
     * Öğrenci oturum kontrolü
     */
    public static function getCurrentStudent()
    {
        $studentId = session('student_id');
        if ($studentId) {
            return Student::find($studentId);
        }
        return null;
    }

    /**
     * Öğrenci oturum kontrolü middleware
     */
    public static function checkStudentAuth()
    {
        return session()->has('student_id');
    }
}