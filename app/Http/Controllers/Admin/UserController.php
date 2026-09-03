<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * نمایش لیست کاربران
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * نمایش فرم ویرایش کاربر
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * به‌روزرسانی کاربر
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Mass Assignment با fill()
        $user->fill($request->only(['name', 'email', 'phone', 'role']));

        // تغییر رمز عبور فقط در صورت وارد شدن
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'کاربر با موفقیت به‌روزرسانی شد.');
    }

    /**
     * تغییر نقش کاربر
     */
    public function updateRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'نقش انتخاب شده معتبر نیست.');
        }

        $user->role = $request->role;
        $user->save();

        return redirect()->back()
            ->with('success', 'نقش کاربر با موفقیت تغییر کرد.');
    }

    /**
     * حذف کاربر
     */
    public function destroy(User $user)
    {
        // جلوگیری از حذف خود کاربر
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'نمی‌توانید خودتان را حذف کنید.');
        }

        $user->delete();
        
        return redirect()->route('admin.users')
            ->with('success', 'کاربر با موفقیت حذف شد.');
    }
}