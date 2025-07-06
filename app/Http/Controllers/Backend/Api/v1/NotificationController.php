<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->id();
        Notification::where('user_id', $user)->where('type', 'connected')->update(['is_read' => true]);
        $notifications = Notification::where('user_id', $user)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $notifications,
        ]);
    }
}
