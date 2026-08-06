<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'expert_id', 'outcome', 'loss_reason', 'final_price', 'contract_date'
    ];

    protected $casts = [
        'contract_date' => 'date',
    ];

   public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}

public function expert()
{
    return $this->belongsTo(User::class, 'expert_id');
}
}