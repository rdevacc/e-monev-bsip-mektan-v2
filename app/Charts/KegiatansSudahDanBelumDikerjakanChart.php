<?php

namespace App\Charts;

use App\Models\Kegiatan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;

class KegiatansSudahdanBelumDikerjakanChart
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

        // Looping for monthly undone activities count
        for ($i = 1; $i <= $bulan; $i++) {
            $totalBelum = Kegiatan::whereYear('created_at', $tahun)->whereMonth('created_at', $i)->Where('status_id', 1)->count('id');
            $totalSudah = Kegiatan::whereYear('created_at', $tahun)->whereMonth('created_at', $i)->Where('status_id',2)->count('id');

            $dataBulan[] = Carbon::create()->month($i)->translatedFormat('F');
            $dataTotalBelum[] = $totalBelum;
            $dataTotalSudah[] = $totalSudah;
        }

        return $this->chartSudah->barChart()
            ->setTitle('Data Kegiatan yang Sudah dan Belum Dikerjakan Tahun ' . $tahun)
            ->setSubtitle('Kegiatan Perbulan.')
            ->setDataset([
                [
                    'name'  =>  'Kegiatan Belum Dikerjakan',
                    'data'  =>  $dataTotalBelum
                ],
                [
                    'name'  =>  'Kegiatan Sudah Dikerjakan',
                    'data'  =>  $dataTotalSudah
                ]
            ])
            ->setDataLabels()
            ->setColors(['#ff771d', '#076da4'])
            ->setXAxis($dataBulan)
            ->setHeight(300);
    }
}
