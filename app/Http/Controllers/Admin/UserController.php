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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20|unique:users,mobile,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
            'is_active' => 'required|in:0,1', // محدود کردن به فقط ۰ یا ۱
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => (int)$validated['is_active'], // تبدیل قطعی به عدد
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users')
                         ->with('success', 'اطلاعات کاربر با موفقیت به‌روزرسانی شد.');
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