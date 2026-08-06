<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'visit_date', 'address', 'region', 'usage_type',
        'building_type',
        'floors', 'blocks',
        // افراد کلیدی
        'has_project_manager',
        'project_manager', 'project_manager_mobile',
        'has_site_manager',
        'site_manager', 'site_manager_mobile',
        'has_facilities_supervisor',
        'facilities_supervisor', 'facilities_supervisor_mobile',
        'has_purchasing_manager',
        'purchasing_manager', 'purchasing_manager_mobile',
        'has_mechanical_consultant',
        'mechanical_consultant', 'mechanical_consultant_mobile',
        'has_hvac_contractor',
        'hvac_contractor', 'hvac_contractor_mobile',
        // پیمانکار، کارفرما، مشاور
        'contractor',
        'employer',
        'consultant',
        // چیلر
        'has_chiller',
        'chiller_brand',
        'chiller_photo',
        // برج خنک‌کن
        'has_cooling_tower',
        'current_cooling_brand',
        'capacity_tr',
        'cooling_tower_photo',
        // رقبا
        'competitors',
        'vendor_list',
        'previous_purchases',
        'competitor_seller',
        'approx_price',
        // امتیازها
        'score_floor',
        'score_building_type',
        'score_facilities_phase',
        'score_purchase_access',
        'score_not_purchased',
        'score_capacity',
        'total_score',
        // وضعیت خرید و سطح
        'purchase_stage',
        'expected_purchase_date',
        'level',
        'notes',
        'user_id',
        'marketer_name',
        'marketer_id',
        // فیلدهای دیتابیس
        'project_level',
        'purchase_status',
        'estimated_purchase_date',
        'project_status',
    ];

    protected $casts = [
        'usage_type' => 'array',
        'building_type' => 'array',
        'expected_purchase_date' => 'date',
        'visit_date' => 'date',
        'has_project_manager' => 'boolean',
        'has_site_manager' => 'boolean',
        'has_facilities_supervisor' => 'boolean',
        'has_purchasing_manager' => 'boolean',
        'has_mechanical_consultant' => 'boolean',
        'has_hvac_contractor' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function latestAssignment()
    {
        return $this->hasOne(Assignment::class)->latest();
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class);
    }
}