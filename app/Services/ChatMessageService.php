<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserMessages;
use App\Models\UserProfileImage;

class ChatMessageService
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function sendMessage($senderUserId, $receiverUserId, $messageContent)
    {
        $message = UserMessages::create([
            'sender_user_id' => $senderUserId,
            'receiver_user_id' => $receiverUserId,
            'message_content' => $messageContent,
            'status' => 'sent',
        ]);
        $createdAt = $message->created_at;
        $date = $createdAt->format('Y-m-d');
        $time = $createdAt->format('h:i:s A');
        $senderUserImage = UserProfileImage::where('user_id', $senderUserId)->where('is_default', '1')->first();
        if ($senderUserImage) {
            $senderUserImage = concatAppUrl($senderUserImage->image_name);
        }
        $messageObject = [
            'id' => $message->id,
            'message_content' => $message->message_content,
            'status' => $message->status,
            'date' => $date,
            'time' => $time,
            'is_me' => $message->sender_user_id == auth()->id() ? true : false,
            'notification_type' => 'chat',
            'sender_user_profile_image' => $senderUserImage,
        ];
        $message['deviceToken'] = User::where('id', $receiverUserId)->value('fcm_token');
        $message['notification_type'] = 'chat';
        $data = $this->notificationService->pushNotification($message->toArray(), $messageObject);
        return $messageObject;
    }

    public function getMessages($userId, $fromUserId, $perPage = 10, $search = null, $sortBy = 'created_at', $sortOrder = 'desc')
    {
        $query = UserMessages::where(function ($query) use ($userId, $fromUserId) {
            $query->where('sender_user_id', $userId)
                ->where('receiver_user_id', $fromUserId);
        })->orWhere(function ($query) use ($userId, $fromUserId) {
            $query->where('sender_user_id', $fromUserId)
                ->where('receiver_user_id', $userId);
        });

        if ($search) {
            $query->where('message_content', 'like', "%{$search}%");
        }

        $data = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
        return $data;
    }

    public function getRecentChatUsers($userId)
    {
        $recentMessages = UserMessages::selectRaw('
            CASE
                WHEN sender_user_id = ? THEN receiver_user_id
                ELSE sender_user_id
            END as user_id, MAX(updated_at) as latest_message_time
        ', [$userId])
            ->where('sender_user_id', $userId)
            ->orWhere('receiver_user_id', $userId)
            ->groupBy('user_id')
            ->toBase();

        $users = User::joinSub($recentMessages, 'recent_messages', function ($join) {
            $join->on('users.id', '=', 'recent_messages.user_id');
        })
            ->join('user_requests', function ($join) use ($userId) {
                $join->where(function ($query) use ($userId) {
                    $query->where('user_requests.from_user_id', $userId)
                        ->orWhere('user_requests.to_user_id', $userId);
                });
            })
            ->where('user_requests.request_status', 'accepted') // Filter for accepted requests
            ->select('users.*', 'recent_messages.latest_message_time')
            ->where('users.id', '!=', $userId)
            ->orderBy('recent_messages.latest_message_time', 'desc')
            ->get();

        $userIds = $users->pluck('id');
        $profileImages = UserProfileImage::whereIn('user_id', $userIds)->get();

        $users->each(function ($user) use ($profileImages) {
            $defaultImage = $profileImages->where('user_id', $user->id)->where('is_default', true)->value('image_name');
            $user->default_profile_picture = null;
            if (!is_null($defaultImage)) {
                $user->default_profile_picture = concatAppUrl($defaultImage);
            }
            $user->profile_images = $profileImages->filter(function ($image) use ($user) {
                return $image->user_id == $user->id;
            })->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => concatAppUrl($image->image_name),
                    'is_default' => boolval($image->is_default),
                ];
            });
        });

        return $users->unique('id')->values()->toArray(); // Convert to array and reset keys
    }
}
