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

    // Casting completed_tasks
    protected function completed_tasks(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }

    // Casting issues
    protected function issues(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    // Casting follow_ups
    protected function follow_ups(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }

    // Casting planned_tasks
    protected function planned_tasks(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }
    
    /**
     * * The Relationship from Monthly Activity to Activity *
     */
    public function monthly_activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
    
}
