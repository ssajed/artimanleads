<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Project;
use App\Models\Assignment;
use App\Models\User;
use App\Models\Notification;

class AssignmentController extends Controller
{
    /**
     * وضعیت‌های نهایی پروژه که توسط Assignment نباید تغییر کنند.
     */
    private const FINAL_STATUSES = ['sold', 'archived', 'lost'];

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'marketer') {
            abort(403, 'بازاریابان مجاز به مشاهده ارجاعات نیستند.');
        }

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
            'assigned_to' => [
                'required',
                Rule::exists('users', 'id')->where('role', 'sales_expert'),
            ],
            'notes' => 'nullable|string',
        ], [
            'assigned_to.exists' => 'کاربر انتخاب‌شده باید نقش کارشناس فروش داشته باشد.',
        ]);

        if (!in_array(auth()->user()->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $project = Project::find($request->project_id);

        // ✅ اصلاح: اگر پروژه در وضعیت نهایی است، از ایجاد Assignment جلوگیری کن
        if (in_array($project->project_status, self::FINAL_STATUSES)) {
            return redirect()->back()->with('error', 'این پروژه در وضعیت نهایی قرار دارد و قابل ارجاع نیست.');
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

        // ✅ اصلاح: فقط در صورتی وضعیت پروژه را تغییر بده که در وضعیت نهایی نباشد
        if (!in_array($project->project_status, self::FINAL_STATUSES)) {
            $project->update(['project_status' => 'assigned']);
        }

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

        if ($assignment->assigned_to != $user->id && !in_array($user->role, ['admin', 'sales_manager'])) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $assignment->update(['status' => $request->status]);
        $project = $assignment->project;

        // ✅ اصلاح: پروژه‌های نهایی توسط Assignment تغییر وضعیت نمی‌دهند
        $isFinalStatus = in_array($project->project_status, self::FINAL_STATUSES);

        $hasOtherActiveAssignments = Assignment::where('project_id', $project->id)
            ->where('id', '!=', $assignment->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($request->status === 'accepted') {
            // فقط اگر پروژه در وضعیت نهایی نیست، به negotiation برود
            if (!$isFinalStatus) {
                $project->update(['project_status' => 'negotiation']);
            }
        } elseif ($request->status === 'rejected') {
            // فقط اگر پروژه در وضعیت نهایی نیست و ارجاع فعال دیگری نیست، به lead برگردد
            if (!$isFinalStatus && !$hasOtherActiveAssignments) {
                $project->update(['project_status' => 'lead']);
            }
        } elseif ($request->status === 'completed') {
            // فقط اگر پروژه در وضعیت نهایی نیست و ارجاع فعال دیگری نیست، archived شود
            if (!$isFinalStatus && !$hasOtherActiveAssignments) {
                $project->update(['project_status' => 'archived']);
            }
        }

        return redirect()->back()->with('success', 'وضعیت ارجاع با موفقیت به‌روزرسانی شد.');
    }
}