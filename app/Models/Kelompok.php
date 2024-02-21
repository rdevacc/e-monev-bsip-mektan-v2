<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nama_kakel',
        'anggaran_kelompok',
    ];

    /**
     * * The Relationship from Kelompok to Subkelompok
     */
    public function tim(): HasMany
    {
        return $this->hasMany(SubKelompok::class);
    }
}
