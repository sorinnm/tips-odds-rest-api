<?php

namespace App\Services;

use App\Models\Fixtures;
use App\Models\TextGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminService
{
    /**
     * @param string $type
     * @return array
     */
    public function getChartDataCurrentWeek(string $type): array
    {
        $chartData = [
            now()->subDays(7)->format('Y-m-d') => 0,
            now()->subDays(6)->format('Y-m-d') => 0,
            now()->subDays(5)->format('Y-m-d') => 0,
            now()->subDays(4)->format('Y-m-d') => 0,
            now()->subDays(3)->format('Y-m-d') => 0,
            now()->subDays(2)->format('Y-m-d') => 0,
            now()->subDays(1)->format('Y-m-d') => 0
        ];

        switch ($type) {
            case 'api-football':
                $data = DB::table('fixtures')
                    ->select(DB::raw('DATE(created_at) as date, count(id) as count'))
                    ->where('status', '=', Fixtures::STATUS_PENDING)
                    ->whereDate('created_at', '>', now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date','desc')
                    ->get();

                break;
            case 'chatgpt':
                $data = DB::table('generations')
                    ->select(DB::raw('DATE(created_at) as date, count(id) as count'))
                    ->where('status', '=', Fixtures::STATUS_PENDING)
                    ->whereDate('created_at', '>', now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date','desc')
                    ->get();
                break;
            case 'wordpress':
                $data = DB::table('generations')
                    ->select(DB::raw('DATE(created_at) as date, count(id) as count'))
                    ->where('status', '=', Fixtures::STATUS_COMPLETE)
                    ->whereNotNull('page_id', )
                    ->whereDate('created_at', '>', now()->subDays(7))
                    ->groupBy('date')
                    ->orderBy('date','desc')
                    ->get();
                break;
            default:
                $data = [];
        }

        foreach ($data as $row) {
            $chartData[$row->date] = $row->count;
        }

        foreach (array_keys($chartData) as $date) {
            $chartData[date('D M j', strtotime($date))] = $chartData[$date];
            unset($chartData[$date]);
        }

        return $chartData;
    }

    public function getStatuses(string $type): array
    {
        switch ($type) {
            case 'fixtures':
                $statuses['completed'] = Fixtures::all()->where('status', '=', Fixtures::STATUS_COMPLETE)->count();
                $statuses['pending'] = Fixtures::all()->where('status', '=', Fixtures::STATUS_PENDING)->count();
                $statuses['error'] = Fixtures::all()->where('status', '=', Fixtures::STATUS_ERROR)->count();
                break;
            case 'generations':
                $statuses['completed'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_COMPLETE)->count();
                $statuses['pending'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_PENDING)->count();
                $statuses['error'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_ERROR)->count();
                break;
            case 'wordpress':
                $statuses['completed'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_COMPLETE)->whereNotNull('page_id')->count();
                $statuses['pending'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_PENDING)->whereNull('page_id')->count();
                $statuses['error'] = TextGenerator::all()->where('status', '=', Fixtures::STATUS_COMPLETE)->whereNull('page_id')->count();
                break;
            default:
                $statuses = [
                    'completed' => 0,
                    'pending' => 0,
                    'error' => 0,
                ];
        }


        return $statuses;
    }
}
