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
            $sheet->mergeCells('A1:Q1');
            $sheet->setCellValue('A1','MONITORING & EVALUASI BULANAN PROGRES KEGIATAN');
            $sheet->mergeCells('A2:Q2');
            $sheet->setCellValue('A2','LINGKUP BBRM MEKTAN');

            // Periode row 3
            $sheet->mergeCells('A3:Q3');
            $startMonth = !empty($this->filters['filterMonth'][0]) ? $this->filters['filterMonth'][0] : '01';
            $endMonth = !empty($this->filters['filterMonth']) ? end($this->filters['filterMonth']) : '12';
            $startYear = !empty($this->filters['filterYear'][0]) ? $this->filters['filterYear'][0] : now()->year;
            $endYear = !empty($this->filters['filterYear']) ? end($this->filters['filterYear']) : now()->year;
            $sheet->setCellValue('A3','Periode ' . $startMonth . '/' . $startYear . ' s.d ' . $endMonth . '/' . $endYear);

            // Header tabel merge row 4-6
            $sheet->mergeCells('A4:A6'); $sheet->setCellValue('A4','No');
            $sheet->mergeCells('B4:B6'); $sheet->setCellValue('B4','Judul Kegiatan');
            $sheet->mergeCells('C4:C6'); $sheet->setCellValue('C4','PJ Kegiatan');
            $sheet->mergeCells('D4:D6'); $sheet->setCellValue('D4','Tim Kerja');
            $sheet->mergeCells('E4:E6'); $sheet->setCellValue('E4','Kelompok Kerja');
            $sheet->mergeCells('F4:F6'); $sheet->setCellValue('F4','Anggaran Kegiatan');
            $sheet->mergeCells('G4:G6'); $sheet->setCellValue('G4','Bulan');

            // Target & Realisasi (Keuangan + Fisik)
            $sheet->mergeCells('H4:M4'); $sheet->setCellValue('H4','Target & Realisasi (Kumulatif)');
            $sheet->mergeCells('H5:K5'); $sheet->setCellValue('H5','Keuangan');
            $sheet->mergeCells('L5:M5'); $sheet->setCellValue('L5','Fisik');

            // Header baris 6
            $sheet->setCellValue('H6','T');   // Target Keuangan Rp
            $sheet->setCellValue('I6','%');   // % Target / Anggaran
            $sheet->setCellValue('J6','R');   // Realisasi Keuangan Rp
            $sheet->setCellValue('K6','%');   // % Realisasi / Anggaran
            $sheet->setCellValue('L6','T');   // Target Fisik
            $sheet->setCellValue('M6','R');   // Realisasi Fisik

            // Kolom lainnya
            $sheet->mergeCells('N4:N6'); $sheet->setCellValue('N4','Kegiatan yang sudah dikerjakan');
            $sheet->mergeCells('O4:O6'); $sheet->setCellValue('O4','Permasalahan');
            $sheet->mergeCells('P4:P6'); $sheet->setCellValue('P4','Tindak Lanjut');

            $lastMonth = !empty($this->filters['filterMonth']) ? max($this->filters['filterMonth']) : now()->month;
            $nextMonth = \Carbon\Carbon::createFromDate($startYear, $lastMonth, 1)->addMonth()->format('F');
            $sheet->mergeCells('Q4:Q6');
            $sheet->setCellValue('Q4', 'Kegiatan yang akan dilakukan (' . $nextMonth . ')');


            // Styling seluruh sheet font 8
            $sheet->getParent()->getDefaultStyle()->getFont()->setSize(8);
            $sheet->getStyle('A1:Q6')->getFont()->setBold(true)->setSize(8);
            $sheet->getStyle('A1:Q6')->getAlignment()->setHorizontal('center')->setVertical('center');

            // Wrap text
            $wrapColumns = ['B','D','E','N','O','P','Q'];
            foreach ($wrapColumns as $col) {
                $sheet->getStyle("{$col}")->getAlignment()->setWrapText(true)->setVertical('top');
            }

            $noWrapColumns = ['A','C','F','G','H','I','J','K','L','M'];
            foreach ($noWrapColumns as $col) {
                $sheet->getStyle("{$col}")->getAlignment()->setWrapText(false)->setVertical('center');
            }

            $activities = $this->getActivities();
            $row = 7;
            $no = 1;

            $formatCurrency = fn($val) => $val !== null ? 'Rp. '.number_format($val,0,',','.') : '-';
            $formatList = function($json) {
                if (is_null($json) || $json === '' || strtolower(trim((string)$json)) === 'null') return '-';
                $arr = json_decode($json,true);
                if (!is_array($arr) || empty($arr)) return '-';
                $arr = array_filter($arr, fn($v) => !is_null($v) && strtolower(trim((string)$v))!=='null' && trim((string)$v)!=='' );
                if (empty($arr)) return '-';
                $out=[]; $counter=1;
                foreach($arr as $v) $out[] = $counter++ . '. ' . (string)$v;
                return implode("\n",$out);
            };

            // Group data per nama kegiatan
            $grouped = [];
            foreach ($activities as $activity) {
                $grouped[$activity->name][] = $activity;
            }

            foreach ($grouped as $name => $items) {
                $startRow = $row;
                foreach ($items as $activity) {
                    $sheet->setCellValue("A{$row}", $row === $startRow ? $no : '');
                    $sheet->setCellValue("B{$row}", $activity->name);
                    $sheet->setCellValue("C{$row}", $activity->pj);
                    $sheet->setCellValue("D{$row}", $activity->work_team);
                    $sheet->setCellValue("E{$row}", $activity->work_group);
                    $sheet->setCellValue("F{$row}", $formatCurrency($activity->activity_budget));
                    $sheet->setCellValue("G{$row}", $activity->monthly_period ? Carbon::parse($activity->monthly_period)->locale('id')->translatedFormat('M') : '-');

                    // Target & Realisasi Keuangan + % dari Anggaran, tetap pakai logika PHP
                    $budget = $activity->activity_budget ?? 0;
                    $financial_target = $activity->financial_target ?? 0;
                    $financial_realization = $activity->financial_realization ?? 0;

                    $sheet->setCellValue("H{$row}", $formatCurrency($financial_target));
                    $sheet->setCellValue("I{$row}", $budget > 0 ? round($financial_target / $budget * 100, 1).'%' : '-');
                    $sheet->setCellValue("J{$row}", $formatCurrency($financial_realization));
                    $sheet->setCellValue("K{$row}", $budget > 0 ? round($financial_realization / $budget * 100, 1).'%' : '-');

                    // Fisik
                    $sheet->setCellValue("L{$row}", $activity->physical_target!==null ? $activity->physical_target.'%' : '-');
                    $sheet->setCellValue("M{$row}", $activity->physical_realization!==null ? $activity->physical_realization.'%' : '-');

                    // List
                    $sheet->setCellValue("N{$row}", $formatList($activity->completed_tasks));
                    $sheet->setCellValue("O{$row}", $formatList($activity->issues));
                    $sheet->setCellValue("P{$row}", $formatList($activity->follow_ups));
                    $sheet->setCellValue("Q{$row}", $formatList($activity->planned_tasks));

                    $row++;
                }

                $endRow = $row - 1;
                if ($endRow > $startRow) {
                    $sheet->mergeCells("A{$startRow}:A{$endRow}");
                    $sheet->mergeCells("B{$startRow}:B{$endRow}");
                    $sheet->mergeCells("C{$startRow}:C{$endRow}");
                    $sheet->mergeCells("D{$startRow}:D{$endRow}");
                    $sheet->mergeCells("E{$startRow}:E{$endRow}");
                    $sheet->getStyle("A{$startRow}:Q{$endRow}")->getAlignment()->setVertical('top');

                    $sheet->getStyle("A4:Q{$endRow}")
                        ->getBorders()->getAllBorders()
                        ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                        ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
                }

                $no++;
            }
        }
    ];
}


}
