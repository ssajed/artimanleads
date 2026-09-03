<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'assigned_by', 'assigned_to', 'status', 'notes'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}