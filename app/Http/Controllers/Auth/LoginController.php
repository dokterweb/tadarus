<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function changePasswordForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
    
        // Cek password lama
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }
    
        // Update password
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
    
        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui!');
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
