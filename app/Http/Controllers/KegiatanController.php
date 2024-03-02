<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\SubKelompok;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

            /**
             * * Check Daterange Filter *
             */

            $thisYear = Carbon::now()->format('Y');
            $date = Carbon::create($thisYear, 1, 1);
            $startOfYear = $date->startOfYear();


            /**
             * * Query for Today Filter *
             */
            if ($request->from_date == $request->end_date) {
                $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->whereDate('kegiatans.created_at', $request->from_date)
                    ->select('kegiatans.*');
            }
            /**
             * * Query for Default Filter, From start of the year untill today *
             */
            elseif ($request->form_date == $startOfYear && $request->end_date == Carbon::now()) {
                $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->whereBetween('kegiatans.created_at', [$request->from_date, $request->end_date])
                    ->select('kegiatans.*');
            } 
            /**
             * * Query for Date Range Filter *
             */
            elseif ($request->filled('from_date') && $request->filled('end_date')) {
                $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                    ->whereBetween('kegiatans.created_at', [$request->from_date, $request->end_date])
                    ->select('kegiatans.*');
            }
            /**
             * * Query for All data Filter *
             */
            else {
                $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])->select('kegiatans.*');
            }


            if($request->searchField) {
                $kata = $request->searchField;
                $query = Kegiatan::with(['kelompok', 'subkelompok', 'status', 'pj'])
                ->whereHas('pj', function (Builder $query) use ($kata) {
                    $query->where('users.nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('kelompok', function (Builder $query) use ($kata) {
                    $query->where('kelompoks.nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('subkelompok', function (Builder $query) use ($kata) {
                    $query->where('sub_kelompoks.nama', 'like', '%' . $kata . '%');
                })
                ->orWhereHas('status', function (Builder $query) use ($kata) {
                    $query->where('status_kegiatans.nama', 'like', '%' . $kata . '%');
                })
                ->orWhere('kegiatans.nama', 'like', '%' . $kata . '%')
                ->select('kegiatans.*');
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
        $kelompoks = Kelompok::get(['id', 'nama']);
        $subkelompoks = SubKelompok::get(['id', 'nama']);
        $pjs = User::get(['id', 'nama']);

        return view('apps.kegiatan.create', [
            'kelompoks' => $kelompoks,
            'subkelompoks' => $subkelompoks,
            'pjs' => $pjs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status_id' => 'required',
            'kelompok_id' => 'required',
            'subkelompok_id' => 'required',
            'user_id' => 'required',
            'nama' => 'required',
            'anggaran_kegiatan' => 'required|numeric',
            'target_keuangan' => 'required|numeric',
            'realisasi_keuangan' => 'required|numeric',
            'target_fisik' => 'required|numeric',
            'realisasi_fisik' => 'required|numeric',
            'dones.*' => 'required',
            'problems.*' => 'required',
            'followUp.*' => 'required',
            'todos.*' => 'required',
        ], [
            'status_id.required' => 'Status field is required!',
            'kelompok_id.required' => 'Kelompok field is required!',
            'subkelompok_id.required' => 'Subkelompok field is required!',
            'user_id.required' => 'PJ field is required!',
            'nama.required' => 'Nama field is required!',
            'anggaran_kegiatan.required' => 'Anggaran Kegiatan field is required!',
            'anggaran_kegiatan.number' => 'Anggaran Kegiatan field is required!',
            'target_keuangan.required' => 'Target Keuangan id field is required!',
            'realisasi_keuangan.required' => 'Realisasi Keuangan field is required!',
            'target_fisik.required' => 'Target Fisik field is required!',
            'realisasi_fisik.required' => 'Realisasi Fisik field is required!',
            'dones.*.required' => 'Kegiatan yang sudah dikerjakan field is required!',
            'problems.*.required' => 'Permasalahan field is required!',
            'followUp.*.required' => 'Tindak Lanjut field is required!',
            'todos.*.required' => 'Kegiatan yang akan dilakukan field is required!',
        ]);

        Kegiatan::create($validated);

        return redirect()->route('kegiatan-index')->with('success', 'Kegiatan Berhasil ditambahkan');
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
    public function update(Request $request, Kegiatan $kegiatan)
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
