<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
                                     ->latest()
                                     ->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }

    public function markSingleRead($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('user_id', auth()->id())
                                    ->first();
        if ($notification) {
            $notification->update(['is_read' => true]);
        }
        return redirect()->back()->with('success', 'نوتیفیکیشن خوانده شد.');
    }
}