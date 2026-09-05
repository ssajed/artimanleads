<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     * کاملاً منطبق با SHOW CREATE TABLE projects در Production
     */
    protected $fillable = [
        // اطلاعات پایه
        'title',
        'visit_date',
        'address',
        'region',
        'building_type',
        'usage_type',
        'marketer_name',
        'floors',
        'blocks',

        // افراد کلیدی
        'has_project_manager',
        'project_manager',
        'project_manager_mobile',
        'has_site_manager',
        'site_manager',
        'site_manager_mobile',
        'has_facilities_supervisor',
        'facilities_supervisor',
        'facilities_supervisor_mobile',
        'has_purchasing_manager',
        'purchasing_manager',
        'purchasing_manager_mobile',
        'has_mechanical_consultant',
        'mechanical_consultant',
        'mechanical_consultant_mobile',
        'has_hvac_contractor',
        'hvac_contractor',
        'hvac_contractor_mobile',

        // اطلاعات عمومی
        'contractor',
        'employer',
        'consultant',

        // اطلاعات فنی
        'has_chiller',
        'chiller_brand',
        'chiller_photo',
        'chiller_selected',
        'has_cooling_tower',
        'current_cooling_brand',
        'capacity_tr',
        'cooling_tower_photo',
        'cooling_tower_selected',

        // وضعیت خرید و رقبا
        'estimated_purchase_date',
        'competitors',
        'vendor_list',
        'previous_purchases',
        'competitor_seller',
        'approx_price',

        // امتیازدهی
        'score_floor',
        'score_building_type',
        'score_facilities_phase',
        'score_purchase_access',
        'score_not_purchased',
        'score_capacity',
        'total_score',

        // وضعیت و سطح
        'project_status',
        'level',
        'notes',

        // مالکیت
        'user_id',
        'marketer_id',
    ];

    /**
     * The attributes that should be cast.
     * has_chiller و has_cooling_tower از Cast حذف شدند تا مقادیر yes/no حفظ شوند.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'building_type' => 'array',
        'usage_type' => 'array',
        'visit_date' => 'date',
        'estimated_purchase_date' => 'date',
        'has_project_manager' => 'boolean',
        'has_site_manager' => 'boolean',
        'has_facilities_supervisor' => 'boolean',
        'has_purchasing_manager' => 'boolean',
        'has_mechanical_consultant' => 'boolean',
        'has_hvac_contractor' => 'boolean',
        'chiller_selected' => 'boolean',
        'cooling_tower_selected' => 'boolean',
        'score_floor' => 'integer',
        'score_building_type' => 'integer',
        'score_facilities_phase' => 'integer',
        'score_purchase_access' => 'integer',
        'score_not_purchased' => 'integer',
        'score_capacity' => 'integer',
        'total_score' => 'integer',
        'approx_price' => 'decimal:2',
        'capacity_tr' => 'float',
    ];

    /**
     * رابطه با کاربر ثبت‌کننده (سازنده لید)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * رابطه با بازاریاب اختصاصی پروژه
     */
    public function marketer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marketer_id');
    }

    /**
     * آخرین وضعیت ارجاع پروژه
     */
    public function latestAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->latestOfMany();
    }

    /**
     * تمام تاریخچه ارجاعات
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * تماس‌های ثبت‌شده برای این پروژه
     */
    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class);
    }

    /**
     * مخاطبین مرتبط با پروژه
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}