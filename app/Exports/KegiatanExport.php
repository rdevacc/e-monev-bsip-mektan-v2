<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KegiatanExport implements FromView, WithColumnWidths, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $array_kelompoks;
    protected $array_subkelompoks;
    protected $array_pjs;
    protected $next_month;
    protected $currentMonth;
    protected $currentYear;

    public function __construct($data, $array_kelompoks, $array_subkelompoks, $array_pjs, $currentMonth, $next_month)
    {
        $this->data = $data;
        $this->array_kelompoks = $array_kelompoks;
        $this->array_subkelompoks = $array_subkelompoks;
        $this->array_pjs = $array_pjs;
        $this->currentMonth = $currentMonth;
        $this->next_month = $next_month;
    }

    public function view(): View
    {

        $dataExcel  = $this->data;
        $array_kelompoks = $this->array_kelompoks;
        $array_subkelompoks = $this->array_subkelompoks;
        $array_pjs = $this->array_pjs;
        $currentMonth = $this->currentMonth;
        $next_month = $this->next_month;

        return view('apps.kegiatan.generateExcel', [
            'data' => $dataExcel,
            'array_kelompoks' => $array_kelompoks,
            'array_subkelompoks' => $array_subkelompoks,
            'array_pjs' => $array_pjs,
            'currentMonth' => $currentMonth,
            'next_month' => $next_month,
            'currentYear' => $this->currentYear,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'H' => 45,
            'I' => 45,
            'J' => 45,
            'K' => 45,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A' => ['font' => ['bold' => true,]],
            'A8:A1000' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // 'A7:K1000' => [
            //     'borders' => [
            //         'allBorders' => [
            //             'borderStyle' => Border::BORDER_MEDIUM,
            //         ],
            //     ],
            // ],
            'B10:Z1000' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ],
            '6' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],

            ],
            '7' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            '8' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
