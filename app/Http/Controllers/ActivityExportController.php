<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\WorkGroup;
use App\Models\WorkTeam;
use App\Models\ActivityStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ActivityExportController extends Controller
{
   public function index(Request $request)
    {
        if ($request->ajax()) {

            $periods = (array) ($request->filterPeriod ?? []);

            // Ubah ke array [ ['year'=>2025,'month'=>09], ... ]
            $parsedPeriods = collect($periods)->map(function ($p) {
                return [
                    'year' => date('Y', strtotime($p . '-01')),
                    'month' => date('m', strtotime($p . '-01')),
                    'raw' => $p,
                ];
            });

            $activities = Activity::select(
                'activities.*',
                'monthly_activities.period as monthly_period',
                'monthly_activities.financial_target as monthly_financial_target',
                'monthly_activities.financial_realization as monthly_financial_realization',
                'monthly_activities.physical_target as monthly_physical_target',
                'monthly_activities.physical_realization as monthly_physical_realization',
                'users.name as pj_name',
                'users.role_id as pj_role_id',
                'work_groups.name as work_group_name',
                'work_teams.name as work_team_name',
                'activity_statuses.name as status_nama'
            )
            ->leftJoin('monthly_activities', function($join) use ($parsedPeriods) {
                $join->on('activities.id', '=', 'monthly_activities.activity_id');
                // Hanya filter kalau ada periode dipilih
                if ($parsedPeriods->isNotEmpty()) {
                    $join->where(function($q) use ($parsedPeriods) {
                        foreach ($parsedPeriods as $p) {
                            $q->orWhere(function($q2) use ($p) {
                                $q2->whereYear('monthly_activities.period', $p['year'])
                                ->whereMonth('monthly_activities.period', $p['month']);
                            });
                        }
                    });
                }
            })
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->leftJoin('work_groups', 'activities.work_group_id', '=', 'work_groups.id')
            ->leftJoin('work_teams', 'activities.work_team_id', '=', 'work_teams.id')
            ->leftJoin('activity_statuses', 'activities.status_id', '=', 'activity_statuses.id')
            ->orderBy('activities.created_at','desc');

            // Filter PJ
            $activities->when($request->filterPJ, fn($q) => $q->where('users.id', $request->filterPJ));

            // Filter WorkGroup
            $activities->when($request->filterWorkGroup, fn($q) => $q->where('activities.work_group_id', $request->filterWorkGroup));

            // Filter WorkTeam
            $activities->when($request->filterWorkTeam, fn($q) => $q->where('activities.work_team_id', $request->filterWorkTeam));

            // Text Search
            if ($request->text_search) {
                $search = strtolower($request->text_search);
                $activities->whereRaw('LOWER(activities.name) like ?', ["%{$search}%"]);
            }

            Log::info('Export filter periods: '.json_encode($periods));

            return DataTables::eloquent($activities)
                ->addIndexColumn()
                ->editColumn('pj', fn($data) => $data->pj_name ?? '-')
                ->editColumn('work_group', fn($data) => $data->work_group_name ?? '-')
                ->editColumn('work_team', fn($data) => $data->work_team_name ?? '-')
                ->editColumn('status', fn($data) => $data->status_nama ?? '-')
                ->editColumn('activity_budget', fn($data) => $data->activity_budget ?? '-')
                ->editColumn('activity_budget', function($data) {
                    return $data->activity_budget
                        ? 'Rp. ' . number_format($data->activity_budget, 0, ',', '.')
                        : '-';
                })
                ->editColumn('financial_target', function($data) {
                    $budget = $data->activity_budget;
                    $target = $data->monthly_financial_target;

                    if (!$target) return "Rp. 0 (0%)";

                    $percent = ($budget && $budget > 0)
                        ? round(($target / $budget) * 100, 1)
                        : 0;

                    return 'Rp. ' . number_format($target, 0, ',', '.') . " ({$percent}%)";
                })

                ->editColumn('financial_realization', function($data) {
                    $budget = $data->activity_budget;
                    $realization = $data->monthly_financial_realization;

                    if (!$realization) return "Rp. 0 (0%)";

                    $percent = ($budget && $budget > 0)
                        ? round(($realization / $budget) * 100, 1)
                        : 0;

                    return 'Rp. ' . number_format($realization, 0, ',', '.') . " ({$percent}%)";
                })

                ->editColumn('physical_target', fn($data) =>
                    $data->monthly_physical_target !== null
                        ? str_replace('.', ',', sprintf('%.1f', (float) $data->monthly_physical_target))
                        : '-'
                )
                ->editColumn('physical_realization', fn($data) =>
                    $data->monthly_physical_realization !== null
                        ? str_replace('.', ',', sprintf('%.1f', (float) $data->monthly_physical_realization))
                        : '-'
                )
                ->editColumn('monthly_period', function ($activity) use ($request, $periods) {
                    if ($activity->monthly_period) {
                        return Carbon::parse($activity->monthly_period)->locale('id')->translatedFormat('F Y');
                    }
                    if (!empty($request->filterPeriod)) {
                        return Carbon::createFromFormat('Y-m', $request->filterPeriod[0])->locale('id')->translatedFormat('F Y');
                    }
                    return '-';
                })
                ->editColumn('monthly_completed_tasks', function($activity) {
                    $raw = $activity->monthly_completed_tasks;

                    logger()->debug('DT_PARSER completed_tasks', [
                        'activity_id' => $activity->id,
                        'raw' => $raw,
                    ]);

                    if (empty($raw)) return '-';

                    $arr = json_decode($raw, true) ?? [];

                    $arr = array_filter(array_map(function ($v) {
                        if (is_null($v)) return '';
                        return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                    }, (array)$arr), fn($v) => $v !== '');

                    return empty($arr) ? '-' : array_values($arr);
                })
                ->editColumn('monthly_issues', function($activity) {
                    $raw = $activity->monthly_issues;

                    logger()->debug('DT_PARSER issues', [
                        'activity_id' => $activity->id,
                        'raw' => $raw,
                    ]);

                    if (empty($raw)) return '-';

                    $arr = json_decode($raw, true) ?? [];

                    $arr = array_filter(array_map(function ($v) {
                        if (is_null($v)) return '';
                        return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                    }, (array)$arr), fn($v) => $v !== '');

                    return empty($arr) ? '-' : array_values($arr);
                })
                ->editColumn('monthly_follow_ups', function($activity) {
                    $raw = $activity->monthly_follow_ups;

                    logger()->debug('DT_PARSER follow_ups', [
                        'activity_id' => $activity->id,
                        'raw' => $raw,
                    ]);

                    if (empty($raw)) return '-';

                    $arr = json_decode($raw, true) ?? [];

                    $arr = array_filter(array_map(function ($v) {
                        if (is_null($v)) return '';
                        return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                    }, (array)$arr), fn($v) => $v !== '');

                    return empty($arr) ? '-' : array_values($arr);
                })
                ->editColumn('monthly_planned_tasks', function($activity) {
                    $raw = $activity->monthly_planned_tasks;

                    logger()->debug('DT_PARSER planned_tasks', [
                        'activity_id' => $activity->id,
                        'raw' => $raw,
                    ]);

                    if (empty($raw)) return '-';

                    $arr = json_decode($raw, true) ?? [];

                    $arr = array_filter(array_map(function ($v) {
                        if (is_null($v)) return '';
                        return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                    }, (array)$arr), fn($v) => $v !== '');

                    return empty($arr) ? '-' : array_values($arr);
                })

                ->editColumn('created_at', fn($data) => $data->created_at?->format('d F Y'))
                ->addColumn('action', 'components.admin.button')
                ->rawColumns(['action'])
                ->make(true);
        }

        // Dropdown data untuk filter export (sama seperti index)
        $pjList = User::with('role')->whereHas('activities')->orderBy('name')->get();
        $workGroupList = WorkGroup::with('activities')->whereHas('activities')->orderBy('name')->get();
        $workTeamList = WorkTeam::with('activities')->whereHas('activities')->orderBy('name')->get();

        $currentYear = now()->year;

        $periods = collect(range(1,12))->map(function($month) use ($currentYear) {
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            $tanggal = Carbon::parse("$currentYear-$monthPadded-01");
            $tanggal->locale('id');
            return [
                'id' => "$currentYear-$monthPadded",
                'name' => $tanggal->isoFormat('MMMM YYYY')
            ];
        });

        return view('apps.export.excel.index', compact([
            'pjList',
            'workGroupList',
            'workTeamList',
            'periods',
        ]));
    }
}
