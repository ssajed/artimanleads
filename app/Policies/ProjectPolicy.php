<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * تعیین اینکه آیا کاربر می‌تونه پروژه رو ببینه
     */
    public function view(User $user, Project $project): bool
    {
        // مدیر فروش همه رو می‌بینه
        if ($user->role === 'sales_manager') {
            return true;
        }
        
        // کارشناس فروش فقط لیدهای ارجاع شده به خودش رو می‌بینه
        if ($user->role === 'sales_expert') {
            return $project->assignments()
                ->where('assigned_to', $user->id)
                ->exists();
        }
        
        // بازاریاب فقط لیدهای خودش رو می‌بینه
        return $project->marketer_id === $user->id;
    }

    /**
     * تعیین اینکه آیا کاربر می‌تونه پروژه رو ویرایش کنه
     */
    public function update(User $user, Project $project): bool
    {
        // مدیر فروش می‌تونه همه رو ویرایش کنه
        if ($user->role === 'sales_manager') {
            return true;
        }
        
        // بازاریاب فقط لیدهای خودش رو می‌تونه ویرایش کنه
        return $project->marketer_id === $user->id;
    }

    /**
     * تعیین اینکه آیا کاربر می‌تونه پروژه رو حذف کنه
     */
    public function delete(User $user, Project $project): bool
    {
        // فقط مدیر فروش می‌تونه حذف کنه
        return $user->role === 'sales_manager';
    }
}