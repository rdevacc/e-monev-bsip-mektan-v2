<?php

namespace App\Http\Controllers;

use App\Models\WorkGroup;
use App\Models\WorkTeam;
use Illuminate\Http\Request;

class WorkTeamController extends Controller
{
    public function index(){
        $workTeams = WorkTeam::with('work_group')->orderBy('id')->get();

        return view('apps.work-team.index', compact('workTeams'));
    }

    public function create(){
        $workGroups = WorkGroup::get(['id', 'name']);

        return view('apps.work-team.create', compact('workGroups'));
    }

    public function store(Request $request){
         $validated = $request->validate([
             'work_group_id' => 'required',
             'name' => 'required',
             'team_leader' => 'required',
         ],[
            'work_group_id.required' => 'Kelompok Kerja field is required!',
            'name.required' => 'Nama Tim Kerja field is required!',
            'team_leader.required' => 'Kelompok Kerja field is required!',
         ]
        );

        WorkTeam::create($validated);

        return redirect()->route('work-team.index')->with('success', 'Data Tim Kerja Baru Berhasil Ditambahkan');

    }

    public function edit(WorkTeam $work_team){
        $workGroups = WorkGroup::get(['id', 'name']);

        return view('apps.work-team.edit', compact('work_team', 'workGroups'));
    }

    public function update(Request $request, WorkTeam $work_team){
         $validated = $request->validate([
             'work_group_id' => 'required',
             'name' => 'required',
             'team_leader' => 'required',
         ],[
            'work_group_id.required' => 'Kelompok Kerja field is required!',
            'name.required' => 'Nama Tim Kerja field is required!',
            'team_leader.required' => 'Kelompok Kerja field is required!',
         ]
        );

        WorkTeam::where('id', $work_team->id)->update($validated);

        return redirect()->route('work-team.index')->with('success', 'Data Tim Kerja Berhasil Diupdate');
    }

    public function destroy(WorkTeam $work_team){
        // Destroy data by id
        WorkTeam::destroy($work_team->id);

        return redirect()->route('work-team.index')->with('success', 'Data Tim Kerja Berhasil Dihapus');
    }
}
