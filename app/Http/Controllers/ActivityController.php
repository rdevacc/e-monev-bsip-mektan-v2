<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityStatus;
use App\Models\MonthlyActivity;
use App\Models\User;
use App\Models\WorkGroup;
use App\Models\WorkTeam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
    private function cleanNumber($value)
    {
        if ($value === null) return null;

        // hapus semua karakter kecuali angka dan koma
        $clean = preg_replace('/[^0-9,]/', '', $value);

        // ganti koma jadi titik (decimal separator)
        $clean = str_replace(',', '.', $clean);

        return $clean;
    }

    private function parseJsonArrayField(mixed $raw): array
    {
        // normalisasi null/empty
        if (is_null($raw) || $raw === '') {
            return [];
        }

        // jika sudah array
        if (is_array($raw)) {
            return $raw;
        }

        // jika object => cast ke array
        if (is_object($raw)) {
            $raw = (array) $raw;
        }

        // jika string: coba decode langsung
        if (is_string($raw)) {
            // 1) coba decode langsung
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // jika decoded adalah array sederhana -> return
                if (is_array($decoded)) {
                    // jika bentuknya ['val' => '["a","b"]'] => decode inner
                    if (array_key_exists('val', $decoded) && is_string($decoded['val'])) {
                        $inner = json_decode($decoded['val'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($inner)) {
                            return $inner;
                        }
                        // jika val sudah array
                        if (is_array($decoded['val'])) return $decoded['val'];
                    }
                    return $decoded;
                }
            }

            // 2) coba stripslashes dan decode lagi (kadang double escaped)
            $stripped = stripslashes($raw);
            if ($stripped !== $raw) {
                $decoded2 = json_decode($stripped, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded2)) {
                    return $decoded2;
                }
            }

            // 3) regex cari "val":"[...]"
            if (preg_match('/"val"\s*:\s*"(.+?)"/', $raw, $m)) {
                $valStr = stripslashes($m[1]);
                $inner2 = json_decode($valStr, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($inner2)) {
                    return $inner2;
                }
            }

            // 4) fallback: split by comma (jika string "a, b, c")
            $parts = preg_split('/\s*,\s*/', trim($raw), -1, PREG_SPLIT_NO_EMPTY);
            if ($parts && count($parts) > 0) {
                return $parts;
            }
        }

        return [];
    }

    /**
     * * Display a listing of the resource. *
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // Default periode: bulan sekarang
            $currentPeriod = now()->format('Y-m');

            // Gunakan filterPeriod dari request jika ada
            $period = $request->filterPeriod ?? $currentPeriod;
            $year = date('Y', strtotime($period . '-01'));
            $month = date('m', strtotime($period . '-01'));

            // Query utama
            $activities = Activity::select(
                'activities.*',
                'monthly_activities.period as monthly_period',
                'monthly_activities.financial_target as monthly_financial_target',
                'monthly_activities.financial_realization as monthly_financial_realization',
                'monthly_activities.physical_target as monthly_physical_target',
                'monthly_activities.physical_realization as monthly_physical_realization',
                // 'monthly_activities.completed_tasks as monthly_completed_tasks',
                // 'monthly_activities.issues as monthly_issues',
                // 'monthly_activities.follow_ups as monthly_follow_ups',
                // 'monthly_activities.planned_tasks as monthly_planned_tasks',
                'users.name as pj_name',
                'users.role_id as pj_role_id',
                'work_groups.name as work_group_name',
                'work_teams.name as work_team_name',
                'activity_statuses.name as status_nama'
            )
            ->leftJoin('monthly_activities', function($join) use ($year, $month) {
                $join->on('activities.id', '=', 'monthly_activities.activity_id')
                    ->whereYear('monthly_activities.period', $year)
                    ->whereMonth('monthly_activities.period', $month);
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

            // Logging (opsional)
            Log::info('Filter period: '.$request->filterPeriod);
            Log::info('Filter PJ: '.$request->filterPJ);

            // DataTables
            return DataTables::eloquent($activities)
                ->addIndexColumn()
                ->editColumn('pj', fn($data) => $data->pj_name ?? '-')
                // ->editColumn('work_group', fn($data) => $data->work_group_name ?? '-')
                // ->editColumn('work_team', fn($data) => $data->work_team_name ?? '-')
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
                ->editColumn('monthly_period', function ($activity) use ($request, $period) {
                    if ($activity->monthly_period) {
                        return Carbon::parse($activity->monthly_period)->locale('id')->translatedFormat('F Y');
                    }

                    // fallback ke filterPeriod (misalnya "2025-09")
                    return Carbon::createFromFormat('Y-m', $period)->locale('id')->translatedFormat('F Y');
                })
                // ->editColumn('monthly_completed_tasks', function($activity) {
                //     $raw = $activity->monthly_completed_tasks;

                //     logger()->debug('DT_PARSER completed_tasks', [
                //         'activity_id' => $activity->id,
                //         'raw' => $raw,
                //     ]);

                //     if (empty($raw)) return '-';

                //     $arr = json_decode($raw, true) ?? [];

                //     $arr = array_filter(array_map(function ($v) {
                //         if (is_null($v)) return '';
                //         return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                //     }, (array)$arr), fn($v) => $v !== '');

                //     return empty($arr) ? '-' : array_values($arr);
                // })
                // ->editColumn('monthly_issues', function($activity) {
                //     $raw = $activity->monthly_issues;

                //     logger()->debug('DT_PARSER issues', [
                //         'activity_id' => $activity->id,
                //         'raw' => $raw,
                //     ]);

                //     if (empty($raw)) return '-';

                //     $arr = json_decode($raw, true) ?? [];

                //     $arr = array_filter(array_map(function ($v) {
                //         if (is_null($v)) return '';
                //         return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                //     }, (array)$arr), fn($v) => $v !== '');

                //     return empty($arr) ? '-' : array_values($arr);
                // })
                // ->editColumn('monthly_follow_ups', function($activity) {
                //     $raw = $activity->monthly_follow_ups;

                //     logger()->debug('DT_PARSER follow_ups', [
                //         'activity_id' => $activity->id,
                //         'raw' => $raw,
                //     ]);

                //     if (empty($raw)) return '-';

                //     $arr = json_decode($raw, true) ?? [];

                //     $arr = array_filter(array_map(function ($v) {
                //         if (is_null($v)) return '';
                //         return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                //     }, (array)$arr), fn($v) => $v !== '');

                //     return empty($arr) ? '-' : array_values($arr);
                // })
                // ->editColumn('monthly_planned_tasks', function($activity) {
                //     $raw = $activity->monthly_planned_tasks;

                //     logger()->debug('DT_PARSER planned_tasks', [
                //         'activity_id' => $activity->id,
                //         'raw' => $raw,
                //     ]);

                //     if (empty($raw)) return '-';

                //     $arr = json_decode($raw, true) ?? [];

                //     $arr = array_filter(array_map(function ($v) {
                //         if (is_null($v)) return '';
                //         return strtolower(trim((string)$v)) === 'null' ? '' : trim((string)$v);
                //     }, (array)$arr), fn($v) => $v !== '');

                //     return empty($arr) ? '-' : array_values($arr);
                // })

                ->editColumn('created_at', fn($data) => $data->created_at?->format('d F Y'))
                ->addColumn('action', 'components.admin.button')
                ->rawColumns(['action'])
                ->make(true);
        }

        // Data untuk filter dropdown
        $pjList = User::with('role')->whereHas('activities')->orderBy('name')->get();
        $workGroupList = WorkGroup::with('activities')->whereHas('activities')->orderBy('name')->get();
        $workTeamList = WorkTeam::with('activities')->whereHas('activities')->orderBy('name')->get();

        // Tahun sekarang
        $currentYear = now()->year;

        // Buat array bulan (Y-m) untuk filter
        $periods = collect(range(1,12))->map(function($month) use ($currentYear) {
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            $tanggal = Carbon::parse("$currentYear-$monthPadded-01");
            $tanggal->locale('id'); // set locale Indonesia
            return [
                'id' => "$currentYear-$monthPadded",
                'name' => $tanggal->isoFormat('MMMM YYYY') // tampil nama bulan dalam bahasa Indonesia
            ];
        });

        return view('apps.activity.index', compact([
            'pjList',
            'workGroupList',
            'workTeamList',
            'periods', // supaya di blade bisa set default periode
        ]));
    }

     /**
     * * Show the form for creating a new resource. *
     */
    public function create()
    {
        $this->authorize('create', Activity::class);

        // Initiate Data
        $workGroupList = WorkGroup::orderBy('name')->get(['id', 'name']);
        $workTeamList = WorkTeam::orderBy('name')->get(['id', 'name']);
        $pjList = User::orderBy('name')->get(['id', 'name']);
        $statusList = ActivityStatus::orderBy('name')->get(['id', 'name']);

        return view('apps.activity.create', compact([
            'workGroupList',
            'workTeamList',
            'pjList',
            'statusList',
        ]));
    }

    /**
     * * Store a newly created resource in storage. *
     */
    public function store(Request $request)
    {
        $this->authorize('create', Activity::class);
        
        $request->validate([
            // kolom Activity
            'user_id'         => 'required',
            'work_group_id'   => 'required',
            'work_team_id'    => 'required',
            'status_id'       => 'required',
            'name'            => 'required',
            'activity_budget' => 'nullable',

            // kolom MonthlyActivity pertama
            'period'                => 'required',
            'financial_target'      => 'nullable',
            'financial_realization' => 'nullable',
            'physical_target'       => 'nullable',
            'physical_realization'  => 'nullable',
            'completed_tasks'       => 'array',
            'issues'                => 'array',
            'follow_ups'            => 'array',
            'planned_tasks'         => 'array',
        ]);

        // 1. Simpan activity utama
        $activity = new Activity();
        $activity->user_id         = $request->user_id;
        $activity->work_group_id   = $request->work_group_id;
        $activity->work_team_id    = $request->work_team_id;
        $activity->status_id       = $request->status_id;
        $activity->name            = $request->name;
        $activity->activity_budget = $this->cleanNumber($request->activity_budget);
        $activity->created_by      = auth()->id();
        $activity->updated_by      = auth()->id();
        $activity->save();

        // 2. Simpan monthly pertama
        $monthly = new MonthlyActivity();
        $monthly->activity_id           = $activity->id;
        $monthly->period                = $request->period . '-01 00:00:00';
        $monthly->financial_target      = $this->cleanNumber($request->financial_target);
        $monthly->financial_realization = $this->cleanNumber($request->financial_realization);
        $monthly->physical_target       = $request->physical_target;
        $monthly->physical_realization  = $request->physical_realization;
        $monthly->completed_tasks       = $request->completed_tasks ?? '-';
        $monthly->issues                = $request->issues ?? '-';
        $monthly->follow_ups            = $request->follow_ups ?? '-';
        $monthly->planned_tasks         = $request->planned_tasks ?? '-';
        $monthly->created_by            = auth()->id();
        $monthly->updated_by            = auth()->id();
        $monthly->save();

        return redirect()->route('activity.index')
            ->with('success', 'Kegiatan baru dan laporan bulan pertama berhasil dibuat');
    }

    /**
     * * Show the form for editing the specified resource. *
     */
    public function edit(Activity $activity)
    {
         $this->authorize('update', $activity);

        // Initiate Data
        $workGroupList = WorkGroup::orderBy('name')->get(['id', 'name']);
        $workTeamList = WorkTeam::orderBy('name')->get(['id', 'name']);
        $pjList = User::orderBy('name')->get(['id', 'name']);
        $statusList = ActivityStatus::orderBy('name')->get(['id', 'name']);

        return view('apps.activity.edit', compact([
            'activity',
            'workGroupList',
            'workTeamList',
            'pjList',
            'statusList',
        ]));
    }

    /**
     * * Get Monthly data. *
     */
    public function getMonthlyData(Request $request, $id)
    {
        
        $request->validate([
            'period' => 'required|date_format:Y-m',
        ]);
        
        $activity = Activity::findOrFail($id);
        
        $this->authorize('update', $activity);
        
        $monthly = $activity->monthly_activity()
            ->whereYear('period', date('Y', strtotime($request->period . '-01')))
            ->whereMonth('period', date('m', strtotime($request->period . '-01')))
            ->first();

        $data = [
            'period' => $monthly?->period ?? '',
            'financial_target' => $monthly?->financial_target !== null ? (float) $monthly->financial_target : 0,
            'financial_realization' => $monthly?->financial_realization !== null ? (float) $monthly->financial_realization : 0,
            'physical_target' => $monthly?->physical_target ?? '',
            'physical_realization' => $monthly?->physical_realization ?? '',
            'completed_tasks' => $monthly?->completed_tasks ?? ['-'],
            'issues' => $monthly?->issues ?? ['-'],
            'follow_ups' => $monthly?->follow_ups ?? ['-'],
            'planned_tasks' => $monthly?->planned_tasks ?? ['-'],
        ];

        return response()->json($data);
    }

    /**
     * * Update Monthly data. *
     */
    public function update(Request $request, Activity $activity)
    {
         $this->authorize('update', $activity);

        $request->validate([
            'name' => 'required',
            'work_group_id' => 'required',
            'work_team_id' => 'required',
            'user_id' => 'required',
            'status_id' => 'required',
            'activity_budget' => 'nullable',
            'period' => 'required',
            'financial_target' => 'nullable',
            'financial_realization' => 'nullable',
            'physical_target' => 'nullable',
            'physical_realization' => 'nullable',
            'completed_tasks' => 'nullable|array',
            'issues' => 'nullable|array',
            'follow_ups' => 'nullable|array',
            'planned_tasks' => 'nullable|array',
        ]);

        // return dd($request->user_id);
        // Update activity
        $activity->update([
            'name' => $request->name,
            'work_group_id' => $request->work_group_id,
            'work_team_id' => $request->work_team_id,
            'user_id' => $request->user_id,
            'status_id' => $request->status_id,
            'activity_budget' => parseRupiah($request->activity_budget),
        ]);

        $periodDate = $request->period . '-01';

        $monthly = $activity->monthly_activity()->firstOrNew([
            'period' => $periodDate
        ]);

        $monthly->financial_target = $this->cleanNumber($request->financial_target);
        $monthly->financial_realization = $this->cleanNumber($request->financial_realization);
        $monthly->physical_target = $request->physical_target;
        $monthly->physical_realization = $request->physical_realization;
        $monthly->completed_tasks = $request->completed_tasks ?? [];
        $monthly->issues = $request->issues ?? [];
        $monthly->follow_ups = $request->follow_ups ?? [];
        $monthly->planned_tasks = $request->planned_tasks ?? [];
        $monthly->save();

        return redirect()->route('activity.index')
            ->with('success', 'Activity dan Monthly Activity berhasil diperbarui.');
    }


    /**
     * * Remove the specified resource from storage. *
     */
    public function destroy(Activity $activity)
    {
         $this->authorize('delete', $activity);
        
        // Destroy data by id
        Activity::destroy($activity->id);

        return redirect()->route('activity.index')->with('success', 'Kegiatan Berhasil Dihapus');
    }

    public function clearMonthlyData(Request $request, Activity $activity)
    {
         $this->authorize('update', $activity);

        $monthId = $request->month_id;

        // hapus semua data bulanan untuk bulan tersebut
        $activity->monthly_activities()
            ->where('month_id', $monthId)
            ->update([
                'financial_target' => 0,
                'financial_realization' => 0,
                'physical_target' => 0,
                'physical_realization' => 0,
                'monthly_completed_tasks' => null,
                'monthly_issues' => null,
                'monthly_follow_ups' => null,
                'monthly_planned_tasks' => null,
            ]);

        return response()->json(['message' => 'Data bulanan berhasil dikosongkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity = $activity->find($activity->id);

        $workGroupList = WorkGroup::orderBy('name')->get(['id', 'name']);
        $workTeamList = WorkTeam::orderBy('name')->get(['id', 'name']);
        $pjList = User::orderBy('name')->get(['id', 'name']);
        $statusList = ActivityStatus::orderBy('name')->get(['id', 'name']);

        return view('apps.activity.show',compact([
            'activity',
            'workGroupList',
            'workTeamList',
            'pjList',
            'statusList',
        ]));
    }
}
