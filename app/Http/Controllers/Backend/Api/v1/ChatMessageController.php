<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserMessages;
use App\Models\UserReport;
use App\Models\UserRequest;
use App\Services\ChatMessageService;
use App\Services\GeneralService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Validator;

class ChatMessageController extends Controller
{
    public $chatMessageService;
    public $generalService;

    public function __construct(ChatMessageService $chatMessageService, GeneralService $generalService)
    {
        $this->chatMessageService = $chatMessageService;
        $this->generalService = $generalService;
    }

    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_user_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $isConnected = UserRequest::where(function ($query) {
            $query->where('from_user_id', auth()->id())
                ->where('to_user_id', request()->receiver_user_id);
        })->orWhere(function ($query) {
            $query->where('from_user_id', request()->receiver_user_id)
                ->where('to_user_id', auth()->id());
        })->where('request_status', 'accepted')->latest()->first();


        if (!$isConnected) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'You are not connected with this user!'],
                'data' => null,
            ], 422);
        }

        $checkReportByMe = UserReport::where('reported_by_user_id', $request->receiver_user_id)->where('reported_user_id', auth()->id())->exists();
        if ($checkReportByMe) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['user_reported' => 'You have been reported!'],
                'data' => null,
            ], 422);
        }

        $checkReportForMe = UserReport::where('reported_by_user_id', auth()->id())->where('reported_user_id', $request->receiver_user_id)->exists();

        if ($checkReportForMe) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['user_reported' => 'You can not send mesaage as you reported this user!'],
                'data' => null,
            ], 422);
        }

        $checkMeBlock = UserBlock::where('blocked_by_user_id', $request->receiver_user_id)->where('blocked_user_id', auth()->id())->where('blocked_status', 'blocked')->exists();

        $checkUserBlock = UserBlock::where('blocked_by_user_id', auth()->id())->where('blocked_user_id', $request->receiver_user_id)->where('blocked_status', 'blocked')->exists();

        if ($checkMeBlock) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Can not send message as you have been blocked by this user!'],
                'data' => null,
            ], 422);
        }
        if ($checkUserBlock) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Can not send message as you blocked this user!'],
                'data' => null,
            ], 422);
        }

        $message = $this->chatMessageService->sendMessage(
            auth()->id(),
            $request->input('receiver_user_id'),
            $request->input('message_content')
        );

        return response()->json([
            'success' => true,
            'message' => "Message sent successfully!",
            'error' => null,
            'data' => $message,
        ]);
    }

    public function getMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_user_id' => 'required|exists:users,id',
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
            'sort_by' => 'string|max:255',
            'sort_order' => 'in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $otherUser = User::where('id', $request->from_user_id)->first();
        if (auth()->user()->id == $request->from_user_id || !$otherUser->hasRole('App User')) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'Unable to fetch messages',
                'data' => null,
            ], 403);
        }

        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $messages = $this->chatMessageService->getMessages(
            auth()->id(),
            $request->from_user_id,
            $perPage,
            $request->input('search'),
            $sortBy,
            $sortOrder
        );

        $formattedMessages = $messages->map(function ($message, $isUserReported)use ($request) {
            if ($message->sender_user_id == $request->from_user_id && $message->status == 'sent') {
                $message->status = 'read';
                UserMessages::where('id', $message->id)->update(['status' => 'read']);
            }
                
            $createdAt = $message->created_at;
            $date = $createdAt->format('Y-m-d');
            $time = $createdAt->format('h:i:s A');

            return [
                'id' => $message->id,
                'message_content' => $message->message_content,
                'status' => $message->status,
                'date' => $date,
                'time' => $time,
                'is_me' => $message->sender_user_id == auth()->id() ? true : false,
            ];
        });
        $reportedByMe = UserReport::where('reported_user_id', $request->from_user_id)->where('reported_by_user_id', auth()->id())->exists() ? true : false;

        $isUserReported = UserReport::where(function ($query) use ($request) {
            $query->where('reported_user_id', auth()->id())
                ->where('reported_by_user_id', $request->from_user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('reported_user_id', $request->from_user_id)
                ->where('reported_by_user_id', auth()->id());
        })->exists();

        // $isUserReported = UserReport::where('reported_user_id', $request->from_user_id)
        //     ->where(function ($query) {
        //         $query->where('reported_by_user_id', auth()->id())
        //             ->orWhere('reported_user_id', auth()->id());
        //     })
        //     ->exists();

        return response()->json([
            'success' => true,
            'message' => "Messages fetched successfully!",
            'error' => null,
            'reported_by_me' => $reportedByMe,
            'user_reported' => $isUserReported,
            'data' => $formattedMessages,
            'pagination' => [
                'total' => $messages->total(),
                'per_page' => $messages->perPage(),
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'next_page_url' => $messages->nextPageUrl(),
                'prev_page_url' => $messages->previousPageUrl(),
            ],
        ]);
    }

    public function getRecentChatUsers()
    {
        $userId = auth()->id();
        $recentChatUsers = $this->chatMessageService->getRecentChatUsers($userId);

        return response()->json([
            'success' => true,
            'message' => "Recent chat users fetched successfully!",
            'error' => null,
            'data' => $recentChatUsers,
        ]);
    }
}
