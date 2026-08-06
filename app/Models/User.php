<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'role',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
         return [
            'email_verified_at' => 'datetime',
           // 'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // متد برای دریافت نام کامل
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: $this->name;
    }

    // متدهای بررسی نقش
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSalesManager()
    {
        return $this->role === 'sales_manager';
    }

    public function isSalesExpert()
    {
        return $this->role === 'sales_expert';
    }

    public function isMarketer()
    {
        return $this->role === 'marketer';
    }

    // بررسی دسترسی برای مدیریت کاربران
    public function canManageUsers()
    {
        return $this->role === 'admin';
    }

    // پروژه‌های این کاربر
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // لیست نقش‌ها
    public static function getRoles()
    {
        return [
            'admin' => 'مدیر کل',
            'sales_manager' => 'مدیر فروش',
            'sales_expert' => 'کارشناس فروش',
            'marketer' => 'بازاریاب'
        ];
    }

    public function getRoleLabelAttribute()
    {
        return self::getRoles()[$this->role] ?? $this->role;
    }
}