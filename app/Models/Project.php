<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'customer_name',
        'customer_phone',
        'customer_mobile',
        'source',
        'description',
        'budget',
        'type',
        'status',
        'level',
        'purchase_status',
        'assigned_to',
        'created_by',
        'visit_date',
        'address',
        'district',
        'usage_type',
        'floor_count',
        'block_count',
        'score',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'budget' => 'decimal:2',
    ];

    // ===== Relationships =====
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
