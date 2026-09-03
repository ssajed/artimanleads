<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentDetail extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'type', 'brand', 'model', 'nameplate_photo_path'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}