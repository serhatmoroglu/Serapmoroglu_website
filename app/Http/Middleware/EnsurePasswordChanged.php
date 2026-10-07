<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Geçici şifreyle giren yönetici, şifresini değiştirmeden panele giremez. */
class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->must_change_password) {
            return redirect()->route('admin.password')->with('info', 'Devam etmeden önce yeni bir şifre belirleyin.');
        }
        return $next($request);
    }
}
