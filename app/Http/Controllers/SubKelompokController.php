<?php

namespace App\Http\Controllers;

use App\Models\SubKelompok;
use App\Models\Kelompok;
use Illuminate\Http\Request;

class SubKelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataSubkelompoks = SubKelompok::all();

        return view('apps.subkelompok.index', [
            'dataSubkelompoks' => $dataSubkelompoks,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelompoks = Kelompok::get(['id', 'nama']);

        return view('apps.subkelompok.create', [
            'kelompoks' => $kelompoks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:50',
            'nama_katim' => 'required|max:20',
            'kelompok_id' => 'required',
        ]);

        SubKelompok::create($validated);

        return redirect()->route('subkelompok-index')->with('success', 'New subkelompok has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKelompok $subKelompok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubKelompok $subkelompok)
    {
        $kelompoks = Kelompok::get(['id', 'nama']);

        return view('apps.subkelompok.edit', [
            'kelompoks' => $kelompoks,
            'subkelompok' => $subkelompok,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKelompok $subkelompok)
    {
        $rules = [
            'nama' => 'required|max:50',
            'nama_katim' => 'required|max:20',
            'kelompok_id' => 'required',
        ];

        $validated = $request->validate($rules);

        SubKelompok::where('id', $subkelompok->id)->update($validated);

        return redirect()->route('subkelompok-index')->with('success', $subkelompok->nama . ' has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKelompok $subkelompok)
    {
        SubKelompok::destroy($subkelompok->id);

        return redirect()->route('subkelompok-index')->with('success',  $subkelompok->nama . ' has been deleted!');
    }
}
