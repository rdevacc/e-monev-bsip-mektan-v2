<?php

namespace App\Models;

use Carbon\Carbon;
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
    
    /**
     * Cek apakah masih bisa diedit (umum).
     * Aturan: hanya bisa edit bulan berjalan sampai tgl 1 bulan berikutnya.
     */
    public function canBeEdited(): bool
    {
        $now = now()->locale('id');
        $period = Carbon::parse($this->period);

        // Deadline = tanggal 1 bulan berikutnya
        $deadline = $period->copy()->addMonth()->startOfMonth()->endOfDay();

        return $now->lessThanOrEqualTo($deadline);
    }

    /**
     * Cek apakah target (keuangan/fisik) bisa diupdate.
     * Aturan: hanya bisa di awal tahun untuk input,
     * dan update setiap triwulan I–III (Maret, Juni, September).
     */
    public function canUpdateTarget(): bool
    {
        $now = now()->locale('id');
        $periodYear = Carbon::parse($this->period)->year;

        // hanya tahun yang sama
        if ($now->year !== $periodYear) {
            return false;
        }

        // hanya di bulan Maret, Juni, September
        $allowedMonths = [3, 6, 9];

        return in_array($now->month, $allowedMonths);
    }

}
