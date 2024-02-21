<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Http\Requests\StoreKegiatanRequest;
use App\Http\Requests\UpdateKegiatanRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])->select('kegiatans.*');

            /**
             * * Check Daterange Filter *
             */
            if ($request->filled('from_date') && $request->filled('end_date')) {
                if ($request->from_date == $request->end_date) {
                    $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                        ->whereDate('kegiatans.created_at', $request->from_date)
                        ->select('kegiatans.*');
                } else {
                    $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                        ->whereBetween('kegiatans.created_at', [$request->from_date, $request->end_date])
                        ->select('kegiatans.*');
                }
            }

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKegiatanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKegiatanRequest $request, Kegiatan $kegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        //
    }
}
