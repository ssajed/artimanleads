<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // بررسی نقش کاربر
        if (!isset($user->role) || $user->role !== 'admin') {
            abort(403, 'شما دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }
}