<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8', // Set password minimum 8 karakter
        ],[
            'email.required'    => 'Email harus di isi',
            'email.email'       => 'Email tidak valid',
            'password.required' => 'Password harus di isi',
            'password.min'      => 'Password harus memiliki minimal 8 karakter',
        ]);
        

        if (Auth::attempt($credential)) {
            // dd('berhasil login');
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->withErrors([
            'email'     => 'Tidak sesuai dengan database',
        ])->onlyInput('email');
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
