<?php

namespace App\Charts;

use App\Models\Kegiatan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class MonthlyKegiatansChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $tahun = Date('Y');
        $bulan = Date('m');

        // Looping for monthly activities count
        for ($i = 1; $i <= $bulan; $i++) {
            $totalKegiatan = Kegiatan::whereYear('created_at', $tahun)->whereMonth('created_at', $i)->count('id');

            $dataBulan[] = Carbon::create()->month($i)->translatedFormat('F');
            $dataTotalKegiatan[] = $totalKegiatan;
        }


        return $this->chart->barChart()
            ->setTitle('Data Kegiatan Bulanan Tahun ' . $tahun)
            ->setSubtitle('Total Kegiatan Perbulan.')
            // ->addData('Jumlah Kegiatan', $dataTotalKegiatan)
            ->setDataset([
                [
                    'name'  =>  'Jumlah Kegiatan',
                    'data'  =>  $dataTotalKegiatan
                ]
            ])
            ->setDataLabels()
            ->setColors(['#062d96'])
            ->setXAxis($dataBulan)
            ->setHeight(300);

    }
}
