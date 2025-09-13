<?php

namespace App\Http\Controllers;

use App\Charts\KegiatansSudahDanBelumDikerjakanChart;
use App\Charts\MonthlyKegiatansChart;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(MonthlyKegiatansChart $chart, KegiatansSudahDanBelumDikerjakanChart $chartSudahdanBelum) {

        $activity = Activity::orderBy('status_id')->orderBy('created_at', 'desc')->get(['id', 'name', 'activity_budget', 'created_at', 'status_id']);

        $currentYear = Carbon::parse(now())->translatedFormat('Y');

        $totalSudah = 0;
        $totalBelum = 0;
        $totalAnggaran = 0;

        $jumlahTotalKegiatan = count($activity);

        /**
         * * Looping for Jumlah Total
         */
        foreach ($activity as $data) {
            // Count Total Anggaran
            $totalAnggaran += $data["anggaran_kegiatan"];

            // Count total kegitan yg sudah dan belum
            if($data["status_id"] == 2){
                $totalSudah += 1;
            } else {
                $totalBelum += 1;
            }
        };

        return view('apps.dashboard.index', [
            'kegiatan' => $activity,
            'totalAnggaran' => $totalAnggaran,
            'currentYear' => $currentYear,
            'jumlahTotalKegiatan' => $jumlahTotalKegiatan,
            'totalSudah' => $totalSudah,
            'totalBelum' => $totalBelum,
            'chart' => $chart->build(),
            'chartSudahdanBelum' => $chartSudahdanBelum->build(),
        ]);
    }
}
