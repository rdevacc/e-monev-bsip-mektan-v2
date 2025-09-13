<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\WorkGroup;
use App\Models\WorkTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $activities = Activity::select(
                'activities.*',
                'monthly_activities.financial_target as monthly_financial_target',
                'monthly_activities.financial_realization as monthly_financial_realization',
                'monthly_activities.physical_target as monthly_physical_target',
                'monthly_activities.physical_realization as monthly_physical_realization',
                'monthly_activities.completed_tasks as monthly_completed_tasks',
                'monthly_activities.issues as monthly_issues',
                'monthly_activities.follow_ups as monthly_follow_ups',
                'monthly_activities.planned_tasks as monthly_planned_tasks',
                'users.name as pj_name',
                'users.role_id as pj_role_id',
                'work_groups.name as work_group_name',
                'work_teams.name as work_team_name',
                'activity_statuses.name as status_nama'
            )
            ->leftJoin('monthly_activities', 'activities.id', '=', 'monthly_activities.activity_id')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->leftJoin('work_groups', 'activities.work_group_id', '=', 'work_groups.id')
            ->leftJoin('work_teams', 'activities.work_team_id', '=', 'work_teams.id')
            ->leftJoin('activity_statuses', 'activities.status_id', '=', 'activity_statuses.id');

        // Filter PJ
        $activities->when($request->filterPJ, function ($q) use ($request) {
            $q->where('users.id', $request->filterPJ);
        });

        // Filter WorkGroup
        $activities->when($request->filterWorkGroup, function ($q) use ($request) {
            $q->where('activities.work_group_id', $request->filterWorkGroup);
        });

        // Filter WorkTeam
        $activities->when($request->filterWorkTeam, function ($q) use ($request) {
            $q->where('activities.work_team_id', $request->filterWorkTeam);
        });

         // Text Search
        if ($request->text_search) {
            $activities->where('activities.name', 'like', '%' . $request->text_search . '%');
        }

        return DataTables::eloquent($activities)
            ->addIndexColumn()
            ->editColumn('pj', fn($data) => $data->pj_name ?? '-')
            ->editColumn('work_group', fn($data) => $data->work_group_name ?? '-')
            ->editColumn('work_team', fn($data) => $data->work_team_name ?? '-')
            ->editColumn('status', fn($data) => $data->status_nama ?? '-')
            ->editColumn('activity_budget', fn($data) => $data->activity_budget ?? '-')
            ->editColumn('financial_target', fn($data) => $data->monthly_financial_target ?? '-')
            ->editColumn('financial_realization', fn($data) => $data->monthly_financial_realization ?? '-')
            ->editColumn('physical_target', fn($data) => $data->monthly_physical_target ?? '-')
            ->editColumn('physical_realization', fn($data) => $data->monthly_physical_realization ?? '-')
            ->editColumn('monthly_completed_tasks', fn($data) => $data->monthly_completed_tasks ?? '-')
            ->editColumn('monthly_issues', fn($data) => $data->monthly_monthly_issues ?? '-')
            ->editColumn('monthly_follow_ups', fn($data) => $data->monthly_monthly_follow_ups ?? '-')
            ->editColumn('monthly_planned_tasks', fn($data) => $data->monthly_monthly_planned_tasks ?? '-')
            ->editColumn('created_at', fn($data) => $data->created_at?->format('d F Y'))
            ->addColumn('action', 'components.admin.button')
            ->rawColumns(['action'])
            ->make(true);
        }

        // Data for filters (optional)
        $pjList = User::with('role')->whereHas('activities')->orderBy('name')->get();
        $workGroupList = WorkGroup::with('activities')->whereHas('activities')->orderBy('name')->get();
        $workTeamList = WorkTeam::with('activities')->whereHas('activities')->orderBy('name')->get();

        return view('apps.activity.index', compact([
            'pjList',
            'workGroupList',
            'workTeamList',
        ]));
    }
    
}
