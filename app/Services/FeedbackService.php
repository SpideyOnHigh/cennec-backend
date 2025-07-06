<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserFeedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeedbackService
{
    public function fetch($requestData, $columns)
    {
        $query = UserFeedback::join('users', 'users.id', 'user_feedback.user_id')
            ->leftjoin('feedback_type_masters', 'feedback_type_masters.id', 'user_feedback.feedback_type_id')->select($columns);

        if (! empty($requestData['search']['value'])) {
            $searchValue = $requestData['search']['value'];
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.username', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('user_feedback.created_at', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('user_feedback.rating', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('feedback_type_masters.feedback_title', 'LIKE', '%' . $searchValue . '%')
                    ->orWhere('user_feedback.comment', 'LIKE', '%' . $searchValue . '%');
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

    public function ratingGraph()
    {
        $data = UserFeedback::select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();
        return $data;
    }
}
