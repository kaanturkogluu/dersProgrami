<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Sadece navigasyon kartları gösterilecek
        return view('admin.dashboard');
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
