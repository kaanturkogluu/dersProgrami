<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Controllers\Admin\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Super admin tüm öğrencileri görebilir, normal admin sadece kendi öğrencilerini
        if ($currentUser->isSuperAdmin()) {
            $students = Student::with('admin')->latest()->paginate(15);
        } else {
            $students = Student::where('admin_id', $currentUser->id)->with('admin')->latest()->paginate(15);
        }
        
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'student_number' => 'required|string|unique:students,student_number',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['admin_id'] = Auth::user()->id; // Mevcut admin'e atanır

        $student = Student::create($data);

        // Otomatik hoş geldiniz maili gönder
        MailController::sendWelcomeToNewStudent($student);

        return redirect()->route('admin.students.index')
            ->with('success', 'Öğrenci başarıyla kaydedildi. Sistem giriş şifresi de oluşturuldu ve hoş geldiniz maili gönderildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'student_number' => 'required|string|unique:students,student_number,' . $student->id,
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        
        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }

        // Handle is_active checkbox
        $data['is_active'] = $request->has('is_active');

        $student->update($data);

        // Determine redirect based on where the request came from
        if ($request->has('from_show')) {
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Öğrenci bilgileri başarıyla güncellendi.');
        }

        return redirect()->route('admin.students.index')
            ->with('success', 'Öğrenci bilgileri başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Öğrenci başarıyla silindi.');
    }
}
