<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\SubKelompok;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PDFController extends Controller
{
    public function pagePDF(){
        $kelompoks = 'Semua Kelompok';
        $subkelompoks = 'Seluruh Subkelompok';

        $data = Kegiatan::whereMonth('created_at', '=', now())->latest()->get();

        return view('apps.kegiatan.generatePDF', [
            'data' => $data,
            'kelompoks' => $kelompoks,
            'subkelompoks' => $subkelompoks,
        ]);
    }

    public function generatePDF(Request $request){
        $kelompoks = Kelompok::get(['nama']);
        $subkelompoks = SubKelompok::get(['nama']);

        /**
         * * Handle Export PDF by Search Filter *
         */
        if ($request->filled('PDFDataSearch')) {
            $kata = $request->PDFDataSearch;
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

            $array_kelompoks = [];
            $array_subkelompoks = [];

            foreach ($data as $kegiatan) {
                array_push($array_kelompoks, $kegiatan['kelompok']['nama']);
                array_push($array_subkelompoks, $kegiatan['subkelompok']['nama']);
            };
        }
            
    }
}