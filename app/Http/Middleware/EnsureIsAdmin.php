<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!Auth::user()->is_admin) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya admin yang diperbolehkan.');
        }

        return $next($request);
    }
}