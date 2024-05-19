<?php

namespace App\Charts;

use App\Models\Kegiatan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class KegiatansSudahDikerjakanChart
{
    protected $chartSudah;

    public function __construct(LarapexChart $chartSudah)
    {
        $this->chartSudah = $chartSudah;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $tahun = Date('Y');
        $bulan = Date('m');

        // Looping for monthly done activities count
        for ($i = 1; $i <= $bulan; $i++) {
            $totalSudah = Kegiatan::whereYear('created_at', $tahun)->whereMonth('created_at', $i)->Where('status_id',2)->count('id');

            $dataBulan[] = Carbon::create()->month($i)->translatedFormat('F');
            $dataTotalSudah[] = $totalSudah;
        }


        return $this->chartSudah->barChart()
            ->setTitle('Data Kegiatan Sudah Dikerjakan Tahun ' . $tahun)
            ->setSubtitle('Kegiatan Perbulan.')
            ->setDataset([
                [
                    'name'  =>  'Jumlah Kegiatan',
                    'data'  =>  $dataTotalSudah
                ]
            ])
            ->setDataLabels()
            ->setColors(['#076da4'])
            ->setXAxis($dataBulan)
            ->setHeight(300);
    }
}
