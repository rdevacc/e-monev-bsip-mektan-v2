<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_group_id',
        'name',
        'team_leader',
    ];

    
    /**
     * * The Relationship to Activities
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * * The Relationship to work Group
     */
    public function work_group(): BelongsTo
    {
        return $this->belongsTo(WorkGroup::class);
    }
}
