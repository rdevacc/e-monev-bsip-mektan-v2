<?php

namespace App\Exports;

use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ActivityExport implements WithEvents, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Ambil data activity sesuai filter (sama logika dengan collection sebelumnya)
     */
    protected function getActivities()
    {
        $query = Activity::query()
            ->select(
                'activities.name',
                'users.name as pj',
                'activities.activity_budget',
                'work_groups.name as work_group',
                'work_teams.name as work_team',
                'activity_statuses.name as status',
                'monthly_activities.period as monthly_period',
                'monthly_activities.financial_target',
                'monthly_activities.financial_realization',
                'monthly_activities.physical_target',
                'monthly_activities.physical_realization',
                'monthly_activities.completed_tasks',
                'monthly_activities.issues',
                'monthly_activities.follow_ups',
                'monthly_activities.planned_tasks'
            )
            ->leftJoin('monthly_activities', 'activities.id', '=', 'monthly_activities.activity_id')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->leftJoin('work_groups', 'activities.work_group_id', '=', 'work_groups.id')
            ->leftJoin('work_teams', 'activities.work_team_id', '=', 'work_teams.id')
            ->leftJoin('activity_statuses', 'activities.status_id', '=', 'activity_statuses.id')
            ->orderBy('activities.id', 'asc')
            ->orderBy('monthly_activities.period', 'asc');

        // Apply filters (sama seperti sebelumnya)
        if (!empty($this->filters['filterPJ'])) {
            $query->where('users.id', $this->filters['filterPJ']);
        }
        if (!empty($this->filters['filterWorkGroup'])) {
            $query->where('activities.work_group_id', $this->filters['filterWorkGroup']);
        }
        if (!empty($this->filters['filterWorkTeam'])) {
            $query->where('activities.work_team_id', $this->filters['filterWorkTeam']);
        }
        if (!empty($this->filters['filterMonth']) && !empty($this->filters['filterYear'])) {
            $query->whereIn(DB::raw('MONTH(monthly_activities.period)'), $this->filters['filterMonth'])
                  ->whereIn(DB::raw('YEAR(monthly_activities.period)'), $this->filters['filterYear']);
        }

        return $query->get();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Header utama
                $sheet->mergeCells('A1:N1');
                $sheet->setCellValue('A1','MONITORING & EVALUASI BULANAN PROGRES KEGIATAN');
                $sheet->mergeCells('A2:N2');
                $sheet->setCellValue('A2','LINGKUP BBRM MEKTAN');

                // Periode di row 3
                $sheet->mergeCells('A3:N3');
                $startMonth = !empty($this->filters['filterMonth'][0]) ? $this->filters['filterMonth'][0] : '01';
                $endMonth = !empty($this->filters['filterMonth']) ? $this->filters['filterMonth'][count($this->filters['filterMonth']) - 1] : '12';
                $startYear = !empty($this->filters['filterYear'][0]) ? $this->filters['filterYear'][0] : now()->year;
                $endYear = !empty($this->filters['filterYear']) ? $this->filters['filterYear'][count($this->filters['filterYear']) - 1] : now()->year;
                $sheet->setCellValue('A3','Periode ' . $startMonth . '/' . $startYear . ' s.d ' . $endMonth . '/' . $endYear);

                // Header tabel merge row 4-6
                $sheet->mergeCells('A4:A6'); $sheet->setCellValue('A4','No');
                $sheet->mergeCells('B4:B6'); $sheet->setCellValue('B4','Judul Kegiatan');
                $sheet->mergeCells('C4:C6'); $sheet->setCellValue('C4','Tim Kerja');
                $sheet->mergeCells('D4:D6'); $sheet->setCellValue('D4','Kelompok Kerja');
                $sheet->mergeCells('E4:E6'); $sheet->setCellValue('E4','Anggaran Kegiatan');

                $sheet->mergeCells('F4:J4'); $sheet->setCellValue('F4','Target & Realisasi (Kumulatif)');
                $sheet->mergeCells('F5:H5'); $sheet->setCellValue('F5','Keuangan');
                $sheet->mergeCells('I5:J5'); $sheet->setCellValue('I5','Fisik');
                $sheet->setCellValue('F6','T'); $sheet->setCellValue('G6','R'); $sheet->setCellValue('H6','%');
                $sheet->setCellValue('I6','T'); $sheet->setCellValue('J6','R');

                $sheet->mergeCells('K4:K6'); $sheet->setCellValue('K4','Kegiatan yang sudah dikerjakan');
                $sheet->mergeCells('L4:L6'); $sheet->setCellValue('L4','Permasalahan');
                $sheet->mergeCells('M4:M6'); $sheet->setCellValue('M4','Tindak Lanjut');

                // Tentukan bulan untuk header planned tasks (N)
                $lastMonth = !empty($this->filters['filterMonth']) ? max($this->filters['filterMonth']) : now()->month;
                $nextMonth = \Carbon\Carbon::createFromDate($startYear, $lastMonth, 1)->addMonth()->format('F');
                $sheet->mergeCells('N4:N6');
                $sheet->setCellValue('N4', 'Kegiatan yang akan dilakukan (' . $nextMonth . ')');

                // Styling header
                $sheet->getStyle('A1:N6')->getFont()->setBold(true);
                $sheet->getStyle('A1:N6')->getAlignment()->setHorizontal('center')->setVertical('center');

                // Set wrap text untuk kolom K-N agar newline tampil
                $sheet->getStyle('K:N')->getAlignment()->setWrapText(true)->setVertical('top');

                // Ambil data dan tulis baris mulai row 7
                $activities = $this->getActivities();
                $row = 7;
                $no = 1;

                // helper untuk format currency & list JSON
                $formatCurrency = function($val) {
                    return $val !== null ? 'Rp. '.number_format($val,0,',','.') : '-';
                };

                // helper untuk format completed_tasks dll
                $formatList = function($json) {
                    if (is_null($json) || $json === '' || strtolower(trim((string)$json)) === 'null') {
                        return '-';
                    }

                    $arr = json_decode($json, true);

                    // Kalau hasil decode bukan array, atau array kosong, atau semua elemennya null/string "null"
                    if (!is_array($arr) || empty($arr)) {
                        return '-';
                    }

                    // filter data biar tidak ada null / "null"
                    $arr = array_filter($arr, function ($v) {
                        if (is_null($v)) return false;
                        $v = strtolower(trim((string)$v));
                        return $v !== '' && $v !== 'null';
                    });

                    if (empty($arr)) {
                        return '-';
                    }
                    $out = [];
                    $counter = 1;
                    foreach ($arr as $v) {
                        // pastikan setiap item string
                        $out[] = $counter++ . '. ' . (string) $v;
                    }
                    return implode("\n", $out);
                };

                // helper fungsi tetap sama ($formatCurrency, $formatList)
                $grouped = [];
                foreach ($activities as $i => $activity) {
                    $grouped[$activity->name][] = $activity;
                }

                foreach ($grouped as $name => $items) {
                    $startRow = $row;
                    foreach ($items as $activity) {
                       // isi nomor hanya di baris pertama group
                        if ($row === $startRow) {
                            $sheet->setCellValue("A{$row}", $no);
                        } else {
                            $sheet->setCellValue("A{$row}", '');
                        }

                        // isi judul (akan di-merge nanti)
                        $sheet->setCellValue("B{$row}", $activity->name);

                        // sisanya isi per-baris
                        $sheet->setCellValue("C{$row}", $activity->work_team);
                        $sheet->setCellValue("D{$row}", $activity->work_group);
                        $sheet->setCellValue("E{$row}", $formatCurrency($activity->activity_budget));
                        $sheet->setCellValue("F{$row}", $formatCurrency($activity->financial_target));
                        $sheet->setCellValue("G{$row}", $formatCurrency($activity->financial_realization));

                        $budget = $activity->activity_budget ?? 0;
                        $financial_realization = $activity->financial_realization ?? 0;
                        $sheet->setCellValue("H{$row}", $budget > 0 ? round($financial_realization / $budget * 100, 1).'%' : '-');

                        $sheet->setCellValue("I{$row}", $activity->physical_target !== null ? $activity->physical_target.'%' : '-');
                        $sheet->setCellValue("J{$row}", $activity->physical_realization !== null ? $activity->physical_realization.'%' : '-');

                        $sheet->setCellValue("K{$row}", $formatList($activity->completed_tasks));
                        $sheet->setCellValue("L{$row}", $formatList($activity->issues));
                        $sheet->setCellValue("M{$row}", $formatList($activity->follow_ups));
                        $sheet->setCellValue("N{$row}", $formatList($activity->planned_tasks));

                        $row++;
                    }
                    $endRow = $row - 1;
                    if ($endRow > $startRow) {
                        // merge No dan Judul
                        $sheet->mergeCells("A{$startRow}:A{$endRow}");
                        $sheet->mergeCells("B{$startRow}:B{$endRow}");
                        $sheet->getStyle("A{$startRow}:B{$endRow}")->getAlignment()->setVertical('center');
                    }

                    // increment per group judul
                    $no++;
                }

            }
        ];
    }
}
