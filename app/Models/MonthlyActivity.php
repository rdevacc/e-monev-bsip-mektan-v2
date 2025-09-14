<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyActivity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'activity_id',
        'financial_target',
        'financial_realization',
        'physical_target',
        'physical_realization',
        'period',
        'completed_tasks',
        'issues',
        'follow_ups',
        'planned_tasks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'completed_tasks' => 'array',
        'issues'          => 'array',
        'follow_ups'      => 'array',
        'planned_tasks'   => 'array',
    ];
    
    /**
     * * The Relationship to Activity *
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
    
}
