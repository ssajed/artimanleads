<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'mobile',
        'position',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}