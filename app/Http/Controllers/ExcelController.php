<?php

namespace App\Http\Controllers;

use App\Exports\KegiatanExport;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function generateExcel(Request $request)
    {
        
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

            /**
             * * This month and Next month logic
             */
            $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
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
            
            return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks, $array_pjs, $currentMonth, $next_month), 'E-Monev BSIP Mektan.xlsx');
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
            
            /**
             * * This month and Next month logic
             */
            $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
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
            
            return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks, $array_pjs, $currentMonth, $next_month), 'E-Monev BSIP Mektan.xlsx');
        };


        /**
         * * Handling Show Data Excel Export
         */
        if($request->excelDataShow){
            $data = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->where('id', $request->excelDataShow)
                    ->get();

            /**
             * * This month and Next month logic
             */
            $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
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
            
            return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks, $array_pjs, $currentMonth, $next_month), 'E-Monev BSIP Mektan.xlsx');
        };


        /**
         * * Data when all conditions passed
         */
        $data = Kegiatan::latest()->get();

        /**
         * * This month and Next month logic
         */
        $currentMonth = Carbon::parse($data[0]["created_at"])->translatedFormat('F');
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
        

        return Excel::download(new KegiatanExport($data, $array_kelompoks, $array_subkelompoks, $array_pjs, $currentMonth, $next_month), 'E-Monev BSIP Mektan.xlsx');
    }
}
