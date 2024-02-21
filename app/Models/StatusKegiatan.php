<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusKegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama'
    ];

    /**
     * * Relationship from Status Kegiatan to User
     */
    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
