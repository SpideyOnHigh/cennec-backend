<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\FeedbackService;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function index()
    {
        return view('backend.feedback.index');
    }

    public function fetch(Request $request)
    {
        $columns = ['users.username', 'user_feedback.created_at', 'user_feedback.comment', 'user_feedback.feedback_type_id', 'feedback_type_masters.feedback_title'];
        $response = $this->feedbackService->fetch($request->all(), $columns);
        $formattedData = [];

        foreach ($response['data'] as $value) {
            $formattedData[] = [
                'username' => $value->username,
                'created_at' => $value->created_at ? $value->created_at->format('Y-m-d') : '',
                'type' => $value->feedback_title,
                'comment' => $value->comment ?? '',
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $formattedData,
        ]);
    }

    public function ratingIndex()
    {
        $user = auth()->user();
        if ($user->can("view-rating")) {
            return view('backend.feedback.feedback-rating');
        }
        return redirect()->back()->with('error', 'You do not have access to do this action. Please try again!');
    }

    public function fetchRating(Request $request)
    {
        $user = auth()->user();
        if ($user->can("view-rating")) {
            $columns = ['users.username', 'user_feedback.created_at', 'user_feedback.rating'];
            $response = $this->feedbackService->fetch($request->all(), $columns);
            $formattedData = [];

            foreach ($response['data'] as $value) {
                $formattedData[] = [
                    'username' => $value->username,
                    'created_at' => $value->created_at ? $value->created_at->format('m/d/Y') : '',
                    'rating' => $value->rating,
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

    public function ratingGraph()
    {
        $data = $this->feedbackService->ratingGraph();
        $response = [
            'labels' => $data->pluck('rating'),
            'values' => $data->pluck('count')
        ];

        return response()->json($response);
    }
}
