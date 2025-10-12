<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Admin listesi
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Super admin tüm adminleri görebilir, normal admin sadece kendini
        if ($currentUser->isSuperAdmin()) {
            $admins = User::where('role', 'admin')->orWhere('role', 'super_admin')->paginate(15);
        } else {
            $admins = collect([$currentUser]);
        }
        
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Yeni admin formu
     */
    public function create()
    {
        // Sadece super admin yeni admin ekleyebilir
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        
        return view('admin.admins.create');
    }

    /**
     * Yeni admin kaydet
     */
    public function store(Request $request)
    {
        // Sadece super admin yeni admin ekleyebilir
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,super_admin',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin başarıyla oluşturuldu.');
    }

    /**
     * Admin detayları
     */
    public function show(User $admin)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm adminleri görebilir, normal admin sadece kendini
        if (!$currentUser->isSuperAdmin() && $currentUser->id !== $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu admin bilgilerini görme yetkiniz bulunmamaktadır.');
        }
        
        // Admin'in öğrencilerini getir
        $students = $admin->students()->with('activeSchedules')->paginate(10);
        
        return view('admin.admins.show', compact('admin', 'students'));
    }

    /**
     * Admin düzenleme formu
     */
    public function edit(User $admin)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm adminleri düzenleyebilir, normal admin sadece kendini
        if (!$currentUser->isSuperAdmin() && $currentUser->id !== $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu admin bilgilerini düzenleme yetkiniz bulunmamaktadır.');
        }
        
        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Admin güncelle
     */
    public function update(Request $request, User $admin)
    {
        $currentUser = Auth::user();
        
        // Super admin tüm adminleri düzenleyebilir, normal admin sadece kendini
        if (!$currentUser->isSuperAdmin() && $currentUser->id !== $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu admin bilgilerini düzenleme yetkiniz bulunmamaktadır.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,super_admin',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'email', 'role', 'is_active']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin bilgileri başarıyla güncellendi.');
    }

    /**
     * Admin sil
     */
    public function destroy(User $admin)
    {
        // Sadece super admin admin silebilir
        if (!Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }
        
        // Kendini silemez
        if (Auth::user()->id === $admin->id) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Kendinizi silemezsiniz.');
        }
        
        // Admin'in öğrencilerini null yap
        $admin->students()->update(['admin_id' => null]);
        
        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin başarıyla silindi.');
    }
}
