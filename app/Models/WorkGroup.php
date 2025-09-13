<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkGroup extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'group_leader',
    ];

    /**
     * * The Relationship to Activities
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * * The Relationship to Work Team
     */
    public function work_teams(): HasMany
    {
        return $this->hasMany(WorkTeam::class);
    }
}
