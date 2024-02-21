<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FilterController extends Controller
{
    public function date_filter(Request $request)
    {
        if ($request->filled('from_date') && $request->filled('end_date')) {
            $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])->whereBetween('kegiatans.created_at', [$request->from_date, $request->end_date])->select('kegiatans.*');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('pj', function ($data) {
                    return $data->pj->nama;
                })
                ->editColumn('kelompok', function ($data) {
                    return $data->kelompok->nama;
                })
                ->editColumn('subkelompok', function ($data) {
                    return $data->subkelompok->nama;
                })
                ->editColumn('status', function ($data) {
                    return $data->status->nama;
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('d F Y');
                })
                ->addColumn('action', 'components.admin.button')
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('apps.kegiatan.index');
    }
}
