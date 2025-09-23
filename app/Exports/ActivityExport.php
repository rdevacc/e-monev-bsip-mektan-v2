<?php

namespace App\Exports;

use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
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
                $sheet->mergeCells('C4:C6'); $sheet->setCellValue('C4','PJ Kegiatan');
                $sheet->mergeCells('D4:D6'); $sheet->setCellValue('D4','Tim Kerja');
                $sheet->mergeCells('E4:E6'); $sheet->setCellValue('E4','Kelompok Kerja');
                $sheet->mergeCells('F4:F6'); $sheet->setCellValue('F4','Anggaran Kegiatan');

                // Kolom Bulan baru
                $sheet->mergeCells('G4:G6'); 
                $sheet->setCellValue('G4','Bulan');

                // Geser header Target & Realisasi ke kanan (sekarang H-L)
                $sheet->mergeCells('H4:L4'); $sheet->setCellValue('H4','Target & Realisasi (Kumulatif)');
                $sheet->mergeCells('H5:J5'); $sheet->setCellValue('H5','Keuangan');
                $sheet->mergeCells('K5:L5'); $sheet->setCellValue('K5','Fisik');
                $sheet->setCellValue('H6','T'); $sheet->setCellValue('I6','R'); $sheet->setCellValue('J6','%');
                $sheet->setCellValue('K6','T'); $sheet->setCellValue('L6','R');

                $sheet->mergeCells('M4:M6'); $sheet->setCellValue('M4','Kegiatan yang sudah dikerjakan');
                $sheet->mergeCells('N4:N6'); $sheet->setCellValue('N4','Permasalahan');
                $sheet->mergeCells('O4:O6'); $sheet->setCellValue('O4','Tindak Lanjut');

                // Planned tasks sekarang pindah ke P
                $lastMonth = !empty($this->filters['filterMonth']) ? max($this->filters['filterMonth']) : now()->month;
                $nextMonth = \Carbon\Carbon::createFromDate($startYear, $lastMonth, 1)->addMonth()->format('F');
                $sheet->mergeCells('P4:P6');
                $sheet->setCellValue('P4', 'Kegiatan yang akan dilakukan (' . $nextMonth . ')');

                // Styling header
                $sheet->getStyle('A1:P6')->getFont()->setBold(true);
                $sheet->getStyle('A1:P6')->getAlignment()->setHorizontal('center')->setVertical('center');

                // Wrap text untuk kolom M-P
                $sheet->getStyle('M:P')->getAlignment()->setWrapText(true)->setVertical('top');



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

                        // isi PJ Kegiatan (akan di-merge juga)
                        $sheet->setCellValue("C{$row}", $activity->pj);

                        // sisanya isi per-baris (geser 1 kolom ke kanan)
                        $sheet->setCellValue("D{$row}", $activity->work_team);
                        $sheet->setCellValue("E{$row}", $activity->work_group);
                        $sheet->setCellValue("F{$row}", $formatCurrency($activity->activity_budget));

                        // isi Bulan
                        $sheet->setCellValue("G{$row}", $activity->monthly_period ? 
                            Carbon::parse($activity->monthly_period)->locale('id')->translatedFormat('F Y') : '-');

                        // lanjutkan target & realisasi mulai kolom H
                        $sheet->setCellValue("H{$row}", $formatCurrency($activity->financial_target));
                        $sheet->setCellValue("I{$row}", $formatCurrency($activity->financial_realization));

                        $budget = $activity->activity_budget ?? 0;
                        $financial_realization = $activity->financial_realization ?? 0;
                        $sheet->setCellValue("J{$row}", $budget > 0 ? round($financial_realization / $budget * 100, 1).'%' : '-');

                        $sheet->setCellValue("K{$row}", $activity->physical_target !== null ? $activity->physical_target.'%' : '-');
                        $sheet->setCellValue("L{$row}", $activity->physical_realization !== null ? $activity->physical_realization.'%' : '-');

                        $sheet->setCellValue("M{$row}", $formatList($activity->completed_tasks));
                        $sheet->setCellValue("N{$row}", $formatList($activity->issues));
                        $sheet->setCellValue("O{$row}", $formatList($activity->follow_ups));
                        $sheet->setCellValue("P{$row}", $formatList($activity->planned_tasks));

                        $row++;
                    }
                    $endRow = $row - 1;
                    if ($endRow > $startRow) {
                        // merge No, Judul, dan PJ
                        $sheet->mergeCells("A{$startRow}:A{$endRow}");
                        $sheet->mergeCells("B{$startRow}:B{$endRow}");
                        $sheet->mergeCells("C{$startRow}:C{$endRow}");
                        $sheet->getStyle("A{$startRow}:P{$endRow}")->getAlignment()->setVertical('top');
                        $sheet->getStyle("A4:P{$endRow}")
                            ->getBorders()->getAllBorders()
                            ->setBorderStyle(Border::BORDER_THIN)
                            ->setColor(new Color('000000'));
                    }

                    // increment per group judul
                    $no++;
                }
            }
        ];
    }
}
