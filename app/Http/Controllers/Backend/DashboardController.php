<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserWhitelistService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $userWhitelistService;

    public function __construct(UserWhitelistService $userWhitelistService)
    {
        $this->userWhitelistService = $userWhitelistService;
    }

    public function userRegistrations()
    {
        $startDate = Carbon::now()->subDays(40);
        $endDate = Carbon::now();

        $data = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        $labels = $data->pluck('date');
        $values = $data->pluck('count');

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
