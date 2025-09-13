<?php

namespace App\Http\Controllers;

use App\Models\WorkGroup;
use Illuminate\Http\Request;

class WorkGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataKelompoks = WorkGroup::all();

        return view('apps.kelompok.index', compact([
            'dataKelompoks'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('apps.kelompok.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:50',
            'nama_kakel' => 'required|max:20',
            'anggaran_kelompok' => 'required|numeric',
        ]);

        WorkGroup::create($validated);

        return redirect()->route('kelompok-index')->with('success', 'New WorkGroup has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkGroup $work_group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkGroup $work_group)
    {
        return view('apps.kelompok.edit', [
            'kelompok' => $work_group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkGroup $work_group)
    {
        $rules = [
            'nama' => 'required|max:50',
            'nama_kakel' => 'required|max:20',
            'anggaran_kelompok' => 'required|numeric',
        ];

        $validated = $request->validate($rules);

        WorkGroup::where('id', $work_group->id)->update($validated);

        return redirect()->route('kelompok-index')->with('success', $work_group->nama . ' has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkGroup $work_group)
    {
        WorkGroup::destroy($work_group->id);

        return redirect()->route('kelompok-index')->with('success',  $work_group->nama . ' has been deleted!');
    }
}
