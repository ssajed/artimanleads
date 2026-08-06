<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpleDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notificationsCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
        
        return view('dashboard', compact('user', 'notificationsCount'));
    }
}
