<?php

namespace App\Http\Controllers;

use App\Exports\KegiatanExport;
use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\SubKelompok;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function generateExcel(Request $request)
    {
        $kelompoks = Kelompok::get(['nama']);
        $subkelompoks = SubKelompok::get(['nama']);


        /**
         * * Handle Export Excel by Search Filter *
         */
        if ($request->filled('excelDataSearch')) {
            $kata = $request->excelDataSearch;
            $data = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                ->whereHas('pj', function (Builder $query) use ($kata) {
                    $query->where('nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('kelompok', function (Builder $query) use ($kata) {
                    $query->where('nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('subkelompok', function (Builder $query) use ($kata) {
                    $query->where('nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('status', function (Builder $query) use ($kata) {
                    $query->where('nama', 'like', '%' . $kata . '%');
                })
                ->orWhere('nama', 'like', '%' . $kata . '%')
                ->get();
            // return dd($data);
            $array_kelompoks = [];
            $array_subkelompoks = [];

            foreach ($data as $kegiatan) {
                array_push($array_kelompoks, $kegiatan['kelompok']['nama']);
                array_push($array_subkelompoks, $kegiatan['subkelompok']['nama']);
            };
            return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks), 'E-Monev BSIP Mektan.xlsx');
        }


        /**
         * * Handle Export Excel by Date Range Filter *
         */
        if ($request->filled('excelDataStart') && $request->filled('excelDataEnd')) {
            if ($request->excelDataStart == $request->excelDataEnd) {
                $data = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->whereDate('created_at', $request->excelDataStart)
                    ->get();
            } else {
                $data = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->whereBetween('created_at', [$request->excelDataStart, $request->excelDataEnd])
                    ->latest()->get();
            }

            $array_kelompoks = [];
            $array_subkelompoks = [];

            foreach ($data as $kegiatan) {
                array_push($array_kelompoks, $kegiatan['kelompok']['nama']);
                array_push($array_subkelompoks, $kegiatan['subkelompok']['nama']);
            };
            return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks), 'E-Monev BSIP Mektan.xlsx');
        };


        $data = Kegiatan::latest()->get();

        return Excel::download(new KegiatanExport($data, $kelompoks, $subkelompoks), 'E-Monev BSIP Mektan.xlsx');
    }

    public function pageExcel()
    {
        // $kelompoks = Kelompok::get(['nama']);
        // $subkelompoks = SubKelompok::get(['nama']);
        $kelompoks = 'Semua Kelompok';
        $subkelompoks = 'Seluruh Subkelompok';

        $data = Kegiatan::whereMonth('created_at', '=', now())->latest()->get();

        return view('apps.kegiatan.generateExcel', [
            'data' => $data,
            'kelompoks' => $kelompoks,
            'subkelompoks' => $subkelompoks,
        ]);
    }
}
