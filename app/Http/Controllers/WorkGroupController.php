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
        $workGroups = WorkGroup::all();

        return view('apps.work-group.index', compact([
            'workGroups'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('apps.work-group.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'group_leader' => 'required|max:20',
        ]);

        WorkGroup::create($validated);

        return redirect()->route('work-group.index')->with('success', 'New WorkGroup has been added!');
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
        return view('apps.work-group.edit', compact([
            'work_group'
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkGroup $work_group)
    {
        $rules = [
            'name' => 'required|max:50',
            'group_leader' => 'required|max:20',
        ];

        $validated = $request->validate($rules);

        WorkGroup::where('id', $work_group->id)->update($validated);

        return redirect()->route('work-group.index')->with('success', $work_group->name . ' has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkGroup $work_group)
    {
        WorkGroup::destroy($work_group->id);

        return redirect()->route('work-group.index')->with('success',  $work_group->name . ' has been deleted!');
    }
}
