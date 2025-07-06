<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\ReportsService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public $reportsService;

    public function __construct(ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->can("view-reports")) {
            return view('backend.reports.index');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetch(Request $request)
    {
        $user = auth()->user();
        if ($user->can("view-reports")) {
            $columns = ['user_reports.reported_by_user_id', 'user_reports.reported_user_id', 'user_reports.created_at', 'user_reports.reason'];
            $response = $this->reportsService->fetch($request->all(), $columns);
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $formattedData[] = [
                    'user' => $value->reported_user_name,
                    'reported_on' => $value->created_at ? $value->created_at->format('m/d/Y') : '',
                    'reported_by' => $value->reported_by_name,
                    'reason' => $value->reason ?? '',
                ];
            }

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $response['total'],
                'recordsFiltered' => $response['total'],
                'data' => $formattedData,
            ]);
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }
}
