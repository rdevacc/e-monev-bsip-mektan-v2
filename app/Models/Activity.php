<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_group_id',
        'work_team_id',
        'status_id',
        'name',
        'activity_budget',
        'created_by',
        'updated_by',
    ];

    /**
     * * Event untuk otomatis mengisi created_by dan updated_by *
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($archive) {
            if (Auth::check()) {
                $archive->created_by = Auth::id();
                $archive->updated_by = Auth::id();
            }
        });

        static::updating(function ($archive) {
            if (Auth::check()) {
                $archive->updated_by = Auth::id();
            }
        });
    }
    
    /**
     * * The Relationship from Activity to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * * The Relationship from Activity to Work Group *
     */
    public function work_group(): BelongsTo
    {
        return $this->belongsTo(WorkGroup::class);
    }

    /**
     * * The Relationship from Activity to Work Team *
     */
    public function work_team(): BelongsTo
    {
        return $this->belongsTo(WorkTeam::class);
    }

    /**
     * * The Relationship from Activity to Activity Status *
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ActivityStatus::class);
    }

    /**
     * * The Relationship from Activity to Monthly Activities *
     */
    public function monthly_activity(): HasMany
    {
        return $this->hasMany(MonthlyActivity::class);
    }

   public function monthly_activity_for_month($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;

        return $this->hasOne(MonthlyActivity::class)
                    ->whereYear('period', $year)
                    ->whereMonth('period', $month)
                    ->withDefault();
    }
}
