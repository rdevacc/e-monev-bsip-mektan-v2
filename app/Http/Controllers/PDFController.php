<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\SubKelompok;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

            /**
             * * This month and Next month logic
             */
            $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
            $currentYear = Carbon::parse($data[0]["created_at"])->translatedFormat('Y');
            $next_month = Carbon::parse($data[0]["created_at"]->addMonth(1))->translatedFormat('F');

            /**
             * * Array value logic
             */

            //  Array variable
            $array_kelompoks = [];
            $array_subkelompoks = [];
            $array_pjs = [];

            // Looping data and insert to empty array
            foreach ($data as $kegiatan) {
                array_push($array_kelompoks, $kegiatan['kelompok']['nama']);
                array_push($array_subkelompoks, $kegiatan['subkelompok']['nama']);
                array_push($array_pjs, $kegiatan['pj']['nama']);
            };
            
            // Handling duplicate value in array
            $array_kelompoks = array_unique($array_kelompoks);
            $array_subkelompoks = array_unique($array_subkelompoks);
            $array_pjs = array_unique($array_pjs);

            $pdf = Pdf::loadView('apps/kegiatan/generatePDF', compact('data', 'next_month', 'currentMonth', 'currentYear', 'array_kelompoks', 'array_subkelompoks', 'array_pjs'));

            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->stream('pdf_file.pdf');
        }



        $data = Kegiatan::latest()->get();

         /**
         * * This month and Next month logic
         */
        $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
        $currentYear = Carbon::parse($data[0]["created_at"])->translatedFormat('Y');
        $next_month = Carbon::parse($data[0]["created_at"]->addMonth(1))->translatedFormat('F');

        /**
         * * Array value logic
         */

        //  Array variable
        $array_kelompoks = [];
        $array_subkelompoks = [];
        $array_pjs = [];

        // Looping data and insert to empty array
        foreach ($data as $kegiatan) {
            array_push($array_kelompoks, $kegiatan['kelompok']['nama']);
            array_push($array_subkelompoks, $kegiatan['subkelompok']['nama']);
            array_push($array_pjs, $kegiatan['pj']['nama']);
        };
        
        // Handling duplicate value in array
        $array_kelompoks = array_unique($array_kelompoks);
        $array_subkelompoks = array_unique($array_subkelompoks);
        $array_pjs = array_unique($array_pjs);

        // return dd($currentYear);

        /**
         * * Generate PDF Page
         */
        $pdf = Pdf::loadView('apps/kegiatan/generatePDF', compact('data', 'next_month', 'currentMonth', 'currentYear', 'array_kelompoks', 'array_subkelompoks', 'array_pjs'));

        $pdf->setPaper('A4', 'landscape');
            
        return $pdf->stream('pdf_file.pdf');
    }
}