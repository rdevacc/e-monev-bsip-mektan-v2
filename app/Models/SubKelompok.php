<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubKelompok extends Model
{
    protected $fillable = [
        'kelompok_id',
        'nama',
        'nama_katim',
    ];

    /**
     * * The Relationship from Subkelompok to Kelompok
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }
}
