<?php

namespace App\Http\Controllers;

use App\Charts\KegiatansBelumDikerjakanChart;
use App\Charts\KegiatansSudahDikerjakanChart;
use App\Charts\MonthlyKegiatansChart;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(MonthlyKegiatansChart $chart, KegiatansSudahDikerjakanChart $chartSudah, KegiatansBelumDikerjakanChart $chartBelum) {

        $kegiatan = Kegiatan::orderBy('status_id')->orderBy('created_at', 'desc')->get(['id', 'nama', 'anggaran_kegiatan', 'created_at', 'status_id']);

        $currentYear = Carbon::parse(now())->translatedFormat('Y');

        $totalSudah = 0;
        $totalBelum = 0;
        $totalAnggaran = 0;

        $jumlahTotalKegiatan = count($kegiatan);

        /**
         * * Looping for Jumlah Total
         */
        foreach ($kegiatan as $data) {
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
            'kegiatan' => $kegiatan,
            'totalAnggaran' => $totalAnggaran,
            'currentYear' => $currentYear,
            'jumlahTotalKegiatan' => $jumlahTotalKegiatan,
            'totalSudah' => $totalSudah,
            'totalBelum' => $totalBelum,
            'chart' => $chart->build(),
            'chartSudah' => $chartSudah->build(),
            'chartBelum' => $chartBelum->build(),
        ]);
    }
}
