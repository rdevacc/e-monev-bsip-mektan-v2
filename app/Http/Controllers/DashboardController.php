<?php

namespace App\Http\Controllers;

use App\Charts\KegiatansSudahDanBelumDikerjakanChart;
use App\Charts\MonthlyKegiatansChart;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index(MonthlyKegiatansChart $chart, KegiatansSudahDanBelumDikerjakanChart $chartSudahdanBelum) {

        $activity = Activity::with(['status', 'monthly_activity'])->orderBy('created_at', 'desc')->get();

        $currentYear = now()->timezone('Asia/Jakarta')->translatedFormat('Y');
        $currentMonth = now()->timezone('Asia/Jakarta')->translatedFormat('m');
        $lastMonth = now()->timezone('Asia/Jakarta')->startOfMonth()->subMonth();

        $totalCompleted = 0;
        $totalIncomplete = 0;
        $totalBudget = 0;
        $totalFinancialTarget = 0;
        $totalFinancialRealization = 0;

        /**
         * * Counting Total of Activities *
         */
        $totalNumberOfActivities = count($activity);

        /**
         * * Looping for Jumlah Total
         */
        foreach ($activity as $data) {            
            // Count Total Anggaran
            $totalBudget += $data["activity_budget"];
            
            // Count total kegitan yg sudah dan belum
            if($data["status_id"] == 2){
                $totalCompleted += 1;
            } else {
                $totalIncomplete += 1;
            }
        };

        foreach ($activity as $data) {
            foreach ($data->monthly_activity as $monthly) {
                $monthlyDate = \Carbon\Carbon::parse($monthly->period);

                if ($monthlyDate->year == $lastMonth->year && $monthlyDate->month == $lastMonth->month) {
                    $totalFinancialTarget      += $monthly->financial_target ?? 0;
                    $totalFinancialRealization += $monthly->financial_realization ?? 0;
                }
            }
        }
        
        /**
         * * Counting Total Financial Target *
        */
        $totalFinancialTargetPercent = $totalBudget ? round(($totalFinancialTarget / $totalBudget * 100), 2) : 0;
        $totalFinancialRealizationPercent = $totalBudget ? round(($totalFinancialRealization / $totalBudget * 100), 2) : 0;

        return view('apps.dashboard.index', [
            'totalBudget' => $totalBudget,
            'currentYear' => $currentYear,
            'totalNumberOfActivities' => $totalNumberOfActivities,
            'totalCompleted' => $totalCompleted,
            'totalIncomplete' => $totalIncomplete,
            'totalFinancialTarget' => $totalFinancialTarget,
            'totalFinancialTargetPercent' => $totalFinancialTargetPercent,
            'totalFinancialRealization' => $totalFinancialRealization,
            'totalFinancialRealizationPercent' => $totalFinancialRealizationPercent,
            'chart' => $chart->build(),
            'chartSudahdanBelum' => $chartSudahdanBelum->build(),
        ]);
    }
}
