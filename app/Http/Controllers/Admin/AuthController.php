<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Admin giriş sayfası
     */
    public function showLoginForm()
    {
        // Eğer zaten giriş yapmışsa dashboard'a yönlendir
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.auth.login');
    }

    /**
     * Admin giriş işlemi
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Hoş geldiniz, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'E-posta adresi veya şifre hatalı.',
        ])->onlyInput('email');
    }

    /**
     * Admin çıkış işlemi
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
