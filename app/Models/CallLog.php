<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'user_id', 'call_date', 'subject', 'contact_person',
        'result', 'next_call_date', 'is_completed', 'notes'
    ];

    protected $casts = [
        'call_date' => 'datetime',
        'next_call_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}