<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // بررسی لاگین بودن کاربر
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // دریافت کاربر فعلی
        $user = Auth::user();

        // بررسی نقش کاربر
        // اگر کاربر نقش admin نداشت، خطای 403 برگردان
        if (!isset($user->role) || $user->role !== 'admin') {
            abort(403, 'شما دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}