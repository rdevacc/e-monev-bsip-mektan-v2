<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelompok_id',
        'subkelompok_id',
        'status_id',
        'nama',
        'anggaran_kegiatan',
        'target_keuangan',
        'realisasi_keuangan',
        'target_fisik',
        'realisasi_fisik',
        'dones',
        'problems',
        'follow_up',
        'todos',
    ];


    // Casting Dones
    protected function dones(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }

    // Casting Problems
    protected function problems(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }

    // Casting Follow Up
    protected function followUp(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }

    // Casting Follow Up
    protected function todos(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),

        );
    }


    /**
     * * The Relationship from Kegiatan to User
     */
    public function pj(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * * The Relationship from Kegiatan to Kelompok
     */
    public function kelompok(): BelongsTo
    {
        return $this->belongsTo(Kelompok::class);
    }

    /**
     * * The Relationship from Kegiatan to Subkelompok
     */
    public function subkelompok(): BelongsTo
    {
        return $this->belongsTo(SubKelompok::class);
    }

    /**
     * * The Relationship from Kegiatan to Subkelompok
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(StatusKegiatan::class);
    }
}
