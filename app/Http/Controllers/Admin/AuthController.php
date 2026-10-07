<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('admin.dashboard') : view('admin.login');
    }

    public function login(Request $request)
    {
        $cred = $request->validate(['email' => ['required', 'email'], 'password' => ['required']]);
        if (! Auth::attempt($cred, $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'E-posta veya şifre hatalı.']);
        }
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function showPassword()
    {
        return view('admin.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ], ['password.min' => 'Yeni şifre en az 10 karakter olmalıdır.', 'password.confirmed' => 'Yeni şifre ile tekrarı eşleşmiyor.']);
        $user = $request->user();
        $user->update(['password' => $request->password, 'must_change_password' => false]);
        return redirect()->route('admin.dashboard')->with('ok', 'Şifreniz güncellendi.');
    }
}
