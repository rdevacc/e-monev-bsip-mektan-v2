<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataKelompoks = Kelompok::all();

        return view('apps.kelompok.index', [
            'dataKelompoks' => $dataKelompoks,
        ]);
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

        Kelompok::create($validated);

        return redirect()->route('kelompok-index')->with('success', 'New kelompok has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelompok $kelompok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelompok $kelompok)
    {
        return view('apps.kelompok.edit', [
            'kelompok' => $kelompok,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelompok $kelompok)
    {
        $rules = [
            'nama' => 'required|max:50',
            'nama_kakel' => 'required|max:20',
            'anggaran_kelompok' => 'required|numeric',
        ];

        $validated = $request->validate($rules);

        Kelompok::where('id', $kelompok->id)->update($validated);

        return redirect()->route('kelompok-index')->with('success', $kelompok->nama . ' has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelompok $kelompok)
    {
        Kelompok::destroy($kelompok->id);

        return redirect()->route('kelompok-index')->with('success',  $kelompok->nama . ' has been deleted!');
    }
}
