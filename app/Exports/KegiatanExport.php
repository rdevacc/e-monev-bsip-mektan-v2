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
    protected $kelompoks;
    protected $subkelompoks;
    protected $next_month;
    protected $currentMonth;
    protected $currentYear;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $dataExcel  = $this->data;

        return view('apps.kegiatan.generateExcel', [
            'data' => $dataExcel,
            'kelompoks' => $this->kelompoks,
            'subkelompoks' => $this->subkelompoks,
            'next_month' => $this->next_month,
            'currentMonth' => $this->currentMonth,
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
            'A10:A1000' => [
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
            'B10:K1000' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_TOP,
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
            '9' => [
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
