<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTechnicalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'mechanical_consultant', 'mep_contractor',
        'competitor_brands', 'competitor_seller', 'competitor_price'
    ];

    protected $casts = [
        'competitor_brands' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}