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

        $currentYear = Carbon::parse(now())->translatedFormat('F');

        $jumlahTotalKegiatan = count($kegiatan);

        return view('apps.dashboard.index', [
            'kegiatan' => $kegiatan,
            'jumlahTotalKegiatan' => $jumlahTotalKegiatan,
            'chart' => $chart->build()
        ]);
    }
}
