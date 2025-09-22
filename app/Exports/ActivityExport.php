<?php

namespace App\Exports;

use App\Models\Activity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Activity::query();

        if (!empty($this->filters['period'])) {
            $query->where('monthly_period', $this->filters['period']);
        }
        if (!empty($this->filters['workGroup'])) {
            $query->where('work_group_id', $this->filters['workGroup']);
        }
        if (!empty($this->filters['workTeam'])) {
            $query->where('work_team_id', $this->filters['workTeam']);
        }
        if (!empty($this->filters['pj'])) {
            $query->where('pj_id', $this->filters['pj']);
        }

        return $query->select([
            'name',
            'pj',
            'activity_budget',
            'work_group',
            'work_team',
            'status',
            'monthly_period',
            'financial_target',
            'financial_realization',
            'physical_target',
            'physical_realization',
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Judul Kegiatan',
            'PJ Kegiatan',
            'Anggaran',
            'Kelompok Kerja',
            'Tim Kerja',
            'Status',
            'Bulan',
            'Target Keuangan',
            'Realisasi Keuangan',
            'Target Fisik',
            'Realisasi Fisik',
        ];
    }
}
