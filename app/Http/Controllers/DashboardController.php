<?php

namespace App\Http\Controllers;

use App\Charts\MonthlyKegiatansChart;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(MonthlyKegiatansChart $chart) {

        $kegiatan = Kegiatan::orderBy('status_id')->orderBy('created_at', 'desc')->get(['id', 'nama', 'todos', 'created_at', 'status_id']);

        $currentYear = Carbon::parse(now())->translatedFormat('Y');

        $totalSudah = 0;
        $totalBelum = 0;

        $jumlahTotalKegiatan = count($kegiatan);

        /**
         * * Looping for Total Sudah dan Total Belum
         */
        foreach ($kegiatan as $data) {
            if($data["status_id"] == 2){
                $totalSudah += 1;
            } else {
                $totalBelum += 1;
            }
        };

        return view('apps.dashboard.index', [
            'kegiatan' => $kegiatan,
            'currentYear' => $currentYear,
            'jumlahTotalKegiatan' => $jumlahTotalKegiatan,
            'totalSudah' => $totalSudah,
            'totalBelum' => $totalBelum,
            'chart' => $chart->build()
        ]);
    }
}
