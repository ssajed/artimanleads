<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $users = [];
        if (auth()->user()->role === 'admin') {
            $users = User::all();
        }
        
        return view('profile.edit', [
            'user' => $request->user(),
            'users' => $users,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'nullable|string|max:20',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $user->id,
        ]);

        // به‌روزرسانی فیلدها
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->mobile = $validated['mobile'] ?? null;
        $user->email = $validated['email'];
        
        // ترکیب نام و نام خانوادگی برای فیلد name
        $user->name = trim($validated['first_name'] . ' ' . $validated['last_name']);
        
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateRole(Request $request): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin,sales_manager,sales_expert,marketer',
        ]);

        if (!auth()->user()->isAdmin()) {
            abort(403, 'شما دسترسی لازم را ندارید.');
        }

        $user = User::find($request->user_id);
        
        // جلوگیری از تغییر نقش خودش
        if ($user->id === auth()->id()) {
            return Redirect::route('profile.edit')->with('error', 'نمی‌توانید نقش خودتان را تغییر دهید.');
        }
        
        $user->role = $request->role;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'role-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}