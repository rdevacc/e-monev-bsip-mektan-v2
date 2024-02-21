<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataRoles = Role::all();

        return view('apps.roles.index', [
            'dataRoles' => $dataRoles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:20',
        ]);

        Role::create($validated);

        return redirect()->route('role-index')->with('success', 'New role has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {

        return view('apps.roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        /**
         * * Validasi Data *
         */
        $rules = [
            'nama' => 'required|max:20',
        ];

        $validated = $request->validate($rules);

        /**
         * * Update data Role *
         */
        Role::where('id', $role->id)->update($validated);


        return redirect()->route('role-index')->with('success', 'Role ' . $role->nama . ' has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        Role::destroy($role->id);

        return redirect()->route('role-index')->with('success', 'Role ' . $role->nama . ' has been deleted!');
    }
}
