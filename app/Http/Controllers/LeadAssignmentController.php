<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\LeadAssignment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadAssignmentController extends Controller
{
    /**
     * نمایش فرم ارجاع (اختیاری - فعلاً از مودال استفاده می‌کنیم)
     */
    public function create(Project $project)
    {
        return view('assignments.create', compact('project'));
    }

    /**
     * ذخیره ارجاع جدید + ارسال نوتیفیکیشن ایمیلی
     */
    public function store(Request $request, Project $project)
    {
        // اعتبارسنجی
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // بررسی اینکه کاربر مقصد نقش مناسب داره
        $assignee = \App\Models\User::find($validated['assigned_to']);
        if (!in_array($assignee->role, ['sales_expert', 'sales_manager'])) {
            abort(403, 'فقط می‌توانید به کارشناس فروش یا مدیر فروش ارجاع دهید.');
        }

        // ایجاد رکورد ارجاع
        $assignment = LeadAssignment::create([
            'project_id' => $project->id,
            'assigned_by' => auth()->id(),
            'assigned_to' => $validated['assigned_to'],
            'status' => 'assigned',
            'notes' => $validated['notes'] ?? null,
        ]);

        // بروزرسانی وضعیت پروژه به "در حال پیگیری" اگر قبلاً بدون استعلام بوده
        if ($project->purchase_status === 'no_inquiry') {
            $project->update(['purchase_status' => 'inquiry']);
        }

        // ایجاد نوتیفیکیشن در دیتابیس
        Notification::create([
            'user_id' => $validated['assigned_to'],
            'type' => 'assignment',
            'data' => [
                'project_id' => $project->id,
                'project_title' => $project->title,
                'assigned_by_name' => auth()->user()->name,
                'message' => "لید «{$project->title}» توسط " . auth()->user()->name . " به شما ارجاع داده شد.",
            ],
        ]);

        // ارسال ایمیل اطلاع‌رسانی
        try {
            Mail::raw("سلام {$assignee->name},\n\nلید جدیدی به شما ارجاع داده شده است:\nپروژه: {$project->title}\nارجاع دهنده: " . auth()->user()->name . "\n\nبرای مشاهده جزئیات وارد پنل شوید.", function ($message) use ($assignee, $project) {
                $message->to($assignee->email)
                        ->subject("🔔 ارجاع لید جدید: {$project->title}");
            });
        } catch (\Exception $e) {
            // اگر ارسال ایمیل مشکل داشت، لاگ بگیر ولی فرآیند قطع نشه
            \Log::error('Failed to send assignment email: ' . $e->getMessage());
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'لید با موفقیت ارجاع داده شد و ایمیل اطلاع‌رسانی ارسال گردید. ✅');
    }

    /**
     * بروزرسانی وضعیت ارجاع (توسط کارشناس فروش)
     */
    public function update(Request $request, LeadAssignment $assignment)
    {
        // فقط کارشناس مربوطه یا مدیر فروش می‌تونه وضعیت رو تغییر بده
        if (auth()->id() !== $assignment->assigned_to && auth()->user()->role !== 'sales_manager') {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:in_progress,won,lost,archived',
            'notes' => 'nullable|string|max:1000',
        ]);

        $assignment->update($validated);

        // اگر وضعیت به برنده/بازنده/بایگانی تغییر کرد، گزارش فروش هم بساز
        if (in_array($validated['status'], ['won', 'lost', 'archived'])) {
            $assignment->project->salesReport()->updateOrCreate(
                ['project_id' => $assignment->project->id],
                [
                    'expert_id' => $assignment->assigned_to,
                    'outcome' => $validated['status'],
                    'loss_reason' => $validated['status'] === 'lost' ? $request->input('loss_reason') : null,
                    'final_price' => $request->input('final_price'),
                    'contract_date' => $request->input('contract_date'),
                ]
            );
        }

        return redirect()->route('projects.show', $assignment->project)
            ->with('success', 'وضعیت ارجاع بروزرسانی شد.');
    }
}