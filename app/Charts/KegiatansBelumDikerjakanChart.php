<?php

namespace App\Charts;

use App\Models\Kegiatan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class KegiatansBelumDikerjakanChart
{
    protected $chartBelum;

    public function __construct(LarapexChart $chartBelum)
    {
        $this->chartBelum = $chartBelum;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $tahun = Date('Y');
        $bulan = Date('m');

        // Looping for monthly done activities count
        for ($i = 1; $i <= $bulan; $i++) {
            $totalBelum = Kegiatan::whereYear('created_at', $tahun)->whereMonth('created_at', $i)->Where('status_id', 1)->count('id');

            $dataBulan[] = Carbon::create()->month($i)->translatedFormat('F');
            $dataTotalBelum[] = $totalBelum;
        }


        return $this->chartBelum->barChart()
            ->setTitle('Data Kegiatan Belum Dikerjakan Tahun ' . $tahun)
            ->setSubtitle('Kegiatan Perbulan.')
            ->setDataset([
                [
                    'name'  =>  'Jumlah Kegiatan',
                    'data'  =>  $dataTotalBelum
                ]
            ])
            ->setDataLabels()
            ->setColors(['#ff771d'])
            ->setXAxis($dataBulan)
            ->setHeight(300);
    }
}
