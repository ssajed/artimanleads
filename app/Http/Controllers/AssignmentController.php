<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Notification;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin' || $user->role === 'sales_manager') {
            $assignments = Assignment::with(['project', 'assignedBy', 'assignedTo'])
                                     ->latest()
                                     ->paginate(20);
        } else {
            $assignments = Assignment::with(['project', 'assignedBy', 'assignedTo'])
                                     ->where('assigned_to', $user->id)
                                     ->latest()
                                     ->paginate(20);
        }

        return view('assignments.index', compact('assignments'));
    }

    public function create(Project $project)
    {
        if (!in_array(auth()->user()->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $experts = User::where('role', 'sales_expert')->get();
        return view('assignments.create', compact('project', 'experts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        if (!in_array(auth()->user()->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $existing = Assignment::where('project_id', $request->project_id)
                              ->where('assigned_to', $request->assigned_to)
                              ->whereIn('status', ['pending', 'accepted'])
                              ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'این لید قبلاً به این کارشناس ارجاع داده شده است.');
        }

        $assignment = Assignment::create([
            'project_id' => $request->project_id,
            'assigned_by' => auth()->id(),
            'assigned_to' => $request->assigned_to,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        $project = Project::find($request->project_id);
        $project->update(['project_status' => 'assigned']);

        // نوتیف برای کارشناس
        Notification::create([
            'user_id' => $request->assigned_to,
            'project_id' => $request->project_id,
            'type' => 'assignment',
            'title' => 'ارجاع جدید',
            'message' => 'یک لید جدید به شما ارجاع داده شده است: ' . $project->title,
            'link' => route('projects.show', $project),
            'is_read' => false,
        ]);

        return redirect()->route('projects.show', $request->project_id)
                         ->with('success', 'لید با موفقیت ارجاع داده شد.');
    }

    public function updateStatus(Request $request, Assignment $assignment)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected,completed',
        ]);

        $user = auth()->user();

        // ===== شرط دسترسی: فقط خود کارشناس یا مدیران =====
        if ($assignment->assigned_to != $user->id && !in_array($user->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        // به‌روزرسانی وضعیت ارجاع
        $assignment->update(['status' => $request->status]);

        // به‌روزرسانی وضعیت پروژه
        $project = $assignment->project;
        
        if ($request->status === 'accepted') {
            $project->update(['project_status' => 'negotiation']);
        } elseif ($request->status === 'rejected') {
            $project->update(['project_status' => 'lead']);
        } elseif ($request->status === 'completed') {
            $project->update(['project_status' => 'archived']);
        }

        return redirect()->back()->with('success', 'وضعیت ارجاع با موفقیت به‌روزرسانی شد.');
    }
}