<?php

namespace App\Services;

use App\Models\UserReport;

class ReportsService
{
    public function fetch($requestData, $columns)
    {
        $query = UserReport::join('users AS reported_by', 'reported_by.id', 'user_reports.reported_by_user_id')
            ->join('users AS reported_user', 'reported_user.id', 'user_reports.reported_user_id')
            ->select('user_reports.*', 'reported_by.username as reported_by_name', 'reported_user.username as reported_user_name');

        if (! empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('reported_by.username', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('reported_user.username', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('user_reports.reason', 'LIKE', '%' . $searchValue . '%');
            });
        }

        $total = $query->count();

        $orderByColumn = $columns[$requestData['order'][0]['column']];
        $orderDirection = $requestData['order'][0]['dir'];
        $query->orderBy($orderByColumn, $orderDirection);

        $start = $requestData['start'];
        $length = $requestData['length'];
        $query->skip($start)->take($length);

        $filteredCount = $query->count();

        $data = $query->get();
        return [
            'data' => $data,
            'total' => $total,
            'filteredCount' => $filteredCount,
        ];
    }
}
