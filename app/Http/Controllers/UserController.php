<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'فقط مدیر سیستم مجاز به مدیریت کاربران است.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'کاربر با موفقیت ایجاد شد.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'کاربر با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(User $user)
    {
        // ✅ اصلاح: جلوگیری از حذف خود
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'شما نمی‌توانید حساب خود را حذف کنید.');
        }

        // ✅ اصلاح: بررسی وابستگی در Projects (به عنوان سازنده)
        $projectsAsCreator = Project::where('user_id', $user->id)->count();
        if ($projectsAsCreator > 0) {
            return redirect()->route('users.index')
                             ->with('error', "این کاربر سازنده {$projectsAsCreator} پروژه است و قابل حذف نیست.");
        }

        // ✅ اصلاح: بررسی وابستگی در Projects (به عنوان بازاریاب)
        $projectsAsMarketer = Project::where('marketer_id', $user->id)->count();
        if ($projectsAsMarketer > 0) {
            return redirect()->route('users.index')
                             ->with('error', "این کاربر بازاریاب {$projectsAsMarketer} پروژه است و قابل حذف نیست.");
        }

        // ✅ اصلاح: بررسی وابستگی در Assignments (به عنوان ارجاع‌دهنده)
        $assignmentsAsAssigner = Assignment::where('assigned_by', $user->id)->count();
        if ($assignmentsAsAssigner > 0) {
            return redirect()->route('users.index')
                             ->with('error', "این کاربر {$assignmentsAsAssigner} ارجاع ثبت کرده است و قابل حذف نیست.");
        }

        // ✅ اصلاح: بررسی وابستگی در Assignments (به عنوان دریافت‌کننده)
        $assignmentsAsAssignee = Assignment::where('assigned_to', $user->id)->count();
        if ($assignmentsAsAssignee > 0) {
            return redirect()->route('users.index')
                             ->with('error', "این کاربر {$assignmentsAsAssignee} ارجاع دریافت کرده است و قابل حذف نیست.");
        }

        // ✅ اگر هیچ وابستگی وجود ندارد، حذف کن
        $user->delete();
        return redirect()->route('users.index')->with('success', 'کاربر با موفقیت حذف شد.');
    }
}