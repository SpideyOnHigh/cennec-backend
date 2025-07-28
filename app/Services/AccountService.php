<?php

namespace App\Services;

use App\Http\Controllers\Backend\PostController;
use App\Models\FeedbackTypeMaster;
use App\Models\InterestMaster;
use App\Models\Notification;
use App\Models\QuestionMaster;
use App\Models\RemovedUser;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserDetail;
use App\Models\UserFavourite;
use App\Models\UserFeedback;
use App\Models\UserInterest;
use App\Models\UserMessages;
use App\Models\UserProfileImage;
use App\Models\UserQuestionAnswer;
use App\Models\UserReport;
use App\Models\UserRequest;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountService
{

    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function deleteMyAccount($userId)
    {
        return User::where('id', $userId)->delete();
    }

    public function blockUser($requestData)
    {
        $data = [
            'blocked_by_user_id' => auth()->id(),
            'blocked_user_id' => $requestData['blocked_user_id'],
            'blocked_status' => 'blocked',
        ];

        return UserBlock::create($data);
    }

    public function reportUser($requestData)
    {
        $data = [
            'reported_by_user_id' => auth()->id(),
            'reported_user_id' => $requestData['reported_user_id'],
            'reason' => 'report',
        ];

        return UserReport::create($data);
    }

    public function favouriteUser($requestData)
    {
        $data = [
            'favorited_by_user_id' => auth()->id(),
            'favorited_user_id' => $requestData['favorited_user_id'],
        ];
        UserFavourite::where('favorited_by_user_id', auth()->id())->where('favorited_user_id', $requestData['favorited_user_id'])->delete();
        return UserFavourite::create($data);
    }

    public function userDetails($userId, $column)
    {
        $userDetail = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
            ->select($column)
            ->where('users.id', $userId)
            ->first();

        if ($userDetail) {
            if (isset($userDetail->dob)) {
                $userDetail->dob = \Carbon\Carbon::parse($userDetail->dob)->format('m-d-Y');
            }

            $userImages = UserProfileImage::where('user_id', $userId)
                ->get();
            $defaultImage = $userImages->where('is_default', true)->value('image_name');
            if (!is_null($defaultImage)) {
                $defaultImage = concatAppUrl($defaultImage);
            } else {
                $defaultImage = null;
            }
            $userDetail->default_profile_picture = $defaultImage;
            $userDetail->profile_pictures = $userImages->map(function ($image) {
                return [
                    'image_id' => $image->id,
                    'image_url' => concatAppUrl($image->image_name),
                    'is_default' => boolval($image->is_default),
                ];
            });

            $mutualInterests = $this->mutualInterests(Auth::id(), $userId);
            $interestsDetails = InterestMaster::whereIn('id', $mutualInterests)
                ->get(['id', 'interest_name', 'interest_color']);
            $userDetail->mutual_interests = $interestsDetails;

            $request = UserRequest::where('from_user_id', Auth::id())
                ->where('to_user_id', $userId)
                ->first();

            if ($request && ($request->request_status === 'pending' || $request->request_status === 'rejected')) {
                $userDetail->request_status = 1;
            } else if ($request && $request->request_status === 'accepted') {
                $userDetail->request_status = 2;
            } else {
                $userDetail->request_status = 0;
            }
            $userDetail->is_sent_request = UserRequest::where('from_user_id', auth()->id())->where('to_user_id', $userId)->where('request_status', 'pending')->exists() ? true : false;

            $userDetail->is_got_request = UserRequest::where('from_user_id', $userId)->where('to_user_id', auth()->id())->where('request_status', 'pending')->exists() ? true : false;

            $userDetail->is_reported = UserReport::where('reported_user_id', $userId)->where('reported_by_user_id', auth()->id())->exists() ? true : false;

            $userDetail->user_comment = UserRequest::where('from_user_id', $userId)->where('to_user_id', auth()->id())->where('request_status', 'pending')->value('request_comment');

            $userDetail->is_favourite = UserFavourite::where('favorited_by_user_id', Auth::id())
                ->where('favorited_user_id', $userId)
                ->exists() ? true : false;

            $isConnected = UserRequest::where(function ($query) use ($userId) {
                $query->where('from_user_id', Auth::id())
                    ->where('to_user_id', $userId);
            })
                ->orWhere(function ($query) use ($userId) {
                    $query->where('from_user_id', $userId)
                        ->where('to_user_id', Auth::id());
                })
                ->where('request_status', 'accepted')
                ->latest()->first();

            $userDetail->is_connected = isset($isConnected) && $isConnected->request_status == 'accepted' ? true : false;

            $userAnswers = UserQuestionAnswer::where('user_id', $userId)->get(['question_id', 'answer']);

            $answersMap = $userAnswers->pluck('answer', 'question_id');
            $questions = QuestionMaster::where('question_status', 1)
                ->orderBy('question_orders')
                ->get(['id', 'question']);

            $questionsWithAnswers = $questions->map(function ($question) use ($answersMap) {
                return [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'answer' => $answersMap->get($question->id, null),
                ];
            });

            $userDetail->questions_with_answers = $questionsWithAnswers;
        }

        return $userDetail;
    }

    public function userInterests($userId)
    {
        return UserInterest::where('user_id', $userId)
            ->pluck('interest_id');
    }

    public function mutualInterests($authUserId, $targetUserId)
    {
        $authUserInterests = $this->userInterests($authUserId);
        $targetUserInterests = $this->userInterests($targetUserId);
        $mutualInterests = $authUserInterests->intersect($targetUserInterests);

        return $mutualInterests;
    }

    public function fetchQuestions()
    {
        return QuestionMaster::where('question_status', '1')->get();
    }

    public function fetchUserQueAns(int $userId)
    {
        $userAnswers = UserQuestionAnswer::where('user_id', $userId)->get();

        $questions = QuestionMaster::where('question_status', 1)
            ->orderBy('question_orders')
            ->get();

        if ($userAnswers->isNotEmpty()) {
            return $questions->map(function ($question) use ($userAnswers) {
                $answer = $userAnswers->firstWhere('question_id', $question->id);
                return [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'answer' => $answer ? $answer->answer : null,
                ];
            });
        }

        return $questions->map(function ($question) {
            return [
                'question_id' => $question->id,
                'question' => $question->question,
                'answer' => null,
            ];
        });
    }


    public function userFeedback($requestData)
    {
        $data = [
            'user_id' => auth()->id(),
            'rating' => $requestData['rating'],
            'feedback_type_id' => $requestData['feedback_type_id'] ?? null,
            'comment' => $requestData['comment'] ?? '',
        ];
        return UserFeedback::create($data);
    }

    public function getUserSettings($userId, $column)
    {
        return User::leftjoin('user_details', 'user_details.user_id', 'users.id')->select($column)->where('users.id', $userId)->first();
    }

    public function updateUserSettings(array $data)
    {
        $userId = $data['user_id'];

        $settings = [
            'is_notification_on' => isset($data['is_notification_on']) ? (int) $data['is_notification_on'] : null,
            'is_display_location' => isset($data['is_display_location']) ? (int) $data['is_display_location'] : null,
            'is_display_age' => isset($data['is_display_age']) ? (int) $data['is_display_age'] : null,
        ];

        $settings = array_filter($settings, function ($value) {
            return $value !== null;
        });

        try {
            UserDetail::updateOrCreate(
                ['user_id' => $userId],
                $settings
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function usersInterest($column)
    {
        return UserInterest::join('interest_masters', 'interest_masters.id', 'user_interests.interest_id')->select($column)->where('user_interests.user_id', auth()->id())->get();
    }

    public function userNotificationCount()
    {
        $count = Notification::where('user_id', auth()->id())->where('is_read', 0)->count();
        return $count ?? 0;
    }

    public function userConnections($userId, $columns)
    {
        $connections1 = UserRequest::join('users', 'users.id', 'user_requests.from_user_id')
            ->select($columns)
            ->where('user_requests.from_user_id', $userId)
            ->where('user_requests.request_status', 'accepted')
            ->get();

        $connections2 = UserRequest::join('users', 'users.id', 'user_requests.to_user_id')
            ->select($columns)
            ->where('user_requests.to_user_id', $userId)
            ->where('user_requests.request_status', 'accepted')
            ->get();

        $allConnections = $connections1->merge($connections2);

        return $allConnections->unique(function ($item) {
            return $item->id;
        });
    }

    public function updateUserProfile(array $data)
    {
        $userId = $data['user_id'];

        $userData = [
            'name' => $data['name'] ?? null,
            // 'username' => $data['username'] ?? null,
        ];

        if (array_key_exists('dob', $data)) {
            $dob = Carbon::createFromFormat('m-d-Y', $data['dob'])->format('Y-m-d');
        }

        $userDetail = [
            'location' => $data['location'] ?? null,
            'location_latitude' => $data['latitude'] ?? null,
            'location_longitude' => $data['longitude'] ?? null,
            'bio' => $data['bio'] ?? null,
            'dob' => isset($data['dob']) ? $dob : null,
            'gender' => isset($data['gender']) ? (int) $data['gender'] : null,
            'is_smoke' => isset($data['is_smoke']) ? (string) $data['is_smoke'] : null,
            'is_drink' => isset($data['is_drink']) ? (string) $data['is_drink'] : null,
        ];

        $userData = array_filter($userData, function ($value) {
            return $value !== null;
        });

        $userDetails = array_filter($userDetail, function ($value) {
            return $value !== null;
        });
        try {
            User::where('id', auth()->id())->update($userData);
            UserDetail::where('user_id', auth()->id())->update($userDetails);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserProfleData($userId)
    {
        $user = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
            ->where('users.id', auth()->id())
            ->select('users.*', 'user_details.*')
            ->first();

        if ($user) {
            if (isset($user->dob)) {
                $user->dob = \Carbon\Carbon::parse($user->dob)->format('m-d-Y');
            }

            $interests = User::leftJoin('user_interests', 'user_interests.user_id', 'users.id')
                ->where('users.id', auth()->id())
                ->pluck('user_interests.interest_id');

            $user->interest_ids = $interests;
        }

        return $user;
    }

    public function updateUserQueAns(array $data)
    {
        $userId = auth()->id();
        foreach ($data as $k => $v) {
            $data = UserQuestionAnswer::updateOrCreate(
                [
                    'user_id' => $userId,
                    'question_id' => $v['question_id']
                ],
                [
                    'answer' => $v['answer']
                ]
            );
        }
        return $data;
    }

    public function storeImages($image, $imageIds)
    {
        if ($image) {
            $fileName = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('user_profile_images', $fileName, 'public');

            UserProfileImage::create([
                'user_id' => auth()->id(),
                'image_name' => $path,
            ]);
        }

        if (!empty($imageIds)) {
            $imagesToDelete = UserProfileImage::whereIn('id', $imageIds)
                ->where('user_id', auth()->id())
                ->get();

            $storagePath = 'public/user_profile_images/';

            foreach ($imagesToDelete as $image) {
                $filePath = $storagePath . basename($image->image_name);

                if (\Storage::disk('public')->exists($filePath)) {
                    \Storage::disk('public')->delete($filePath);
                }
                if ($image->is_default == '1') {
                    $newDefaultImage = UserProfileImage::where('user_id', auth()->id())
                        ->latest()->first();
                    $newDefaultImage->update(['is_default' => true]);
                }
            }

            UserProfileImage::whereIn('id', $imageIds)
                ->where('user_id', auth()->id())
                ->delete();
        }
        return true;
    }

    public function getUserImages(int $userId)
    {
        return UserProfileImage::where('user_id', $userId)
            ->orderBy('id', 'ASC')
            ->limit(3)
            ->get()
            ->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_path' => concatAppUrl($image->image_name),
                    'is_default' => boolval($image->is_default),
                ];
            });
    }

    public function fetchNearUsers()
    {
        $loggedInUser = Auth::user();

        $userLocation = $loggedInUser->details->location ?? null;
        $userInterests = $loggedInUser->interests->pluck('interest_id')->toArray() ?? [];

        if (empty($userInterests)) {
            return collect([]);
        }

        return User::with(['details', 'interests'])
            ->where('id', '!=', $loggedInUser->id)
            ->whereHas('details', function ($query) use ($userLocation) {
                $query->where('location', $userLocation);
            })
            ->whereHas('interests', function ($query) use ($userInterests) {
                $query->whereIn('interest_id', $userInterests);
            })
            ->get()
            ->map(function ($user) use ($userInterests) {
                $userInterestIds = $user->interests->pluck('interest_id')->toArray();
                $mutualInterests = array_intersect($userInterests, $userInterestIds);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'contact' => $user->contact,
                    'apple_id' => $user->apple_id,
                    'google_id' => $user->google_id,
                    'fcm_token' => $user->fcm_token,
                    'email_verified_at' => $user->email_verified_at,
                    'invitation_code_id' => $user->invitation_code_id,
                    'email_otp' => $user->email_otp,
                    'user_status' => $user->user_status,
                    'dob' => $user->details->dob,
                    'gender' => $user->details->gender,
                    'bio' => $user->details->bio,
                    'location' => $user->details->location,
                    'location_latitude' => $user->details->location_latitude,
                    'location_longitude' => $user->details->location_longitude,
                    'is_smoke' => $user->details->is_smoke,
                    'is_drink' => $user->details->is_drink,
                    'is_distance_preference' => $user->details->is_distance_preference,
                    'distance_preference' => $user->details->distance_preference,
                    'is_age_preference' => $user->details->is_age_preference,
                    'from_age_preference' => $user->details->from_age_preference,
                    'to_age_preference' => $user->details->to_age_preference,
                    'is_mutual_interest_preference' => $user->details->is_mutual_interest_preference,
                    'min_mutual_interest' => $user->details->min_mutual_interest,
                    'gender_preference' => $user->details->gender_preference,
                    'is_display_in_search' => $user->details->is_display_in_search,
                    'is_display_in_recommendation' => $user->details->is_display_in_recommendation,
                    'is_display_location' => $user->details->is_display_location,
                    'is_display_age' => $user->details->is_display_age,
                    'is_notification_on' => $user->details->is_notification_on,
                    'is_agree_term_condition' => $user->details->is_agree_term_condition,
                    'mutual_interests' => $mutualInterests,
                ];
            });
    }

    public function fetchFeedbackQue()
    {
        $data = FeedbackTypeMaster::where('feedback_status', '1')->get();
        return $data;
    }

    public function givenRatingForAuthUser()
    {
        $data = UserFeedback::where('user_id', auth()->id())
            ->latest('created_at')
            ->value('rating');
        return $data;
    }

    public function updateUserInterest(array $data)
    {
        $userId = auth()->id();
        $settings = [
            'interest_id' => isset($data['interest_id']) ? $data['interest_id'] : null,
        ];

        try {
            if (count($settings['interest_id']) > 0) {
                UserInterest::where('user_id', $userId)->delete();
                foreach ($settings['interest_id'] as $val) {
                    UserInterest::create(
                        [
                            'user_id' => $userId,
                            'interest_id' => $val
                        ],
                    );
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addToMyInterest(array $data)
    {
        $userId = $data['user_id'];
        $data = [
            'user_id' => $data['user_id'] ?? null,
            'interest_id' => $data['interest_id'] ?? null,
        ];

        try {
            UserInterest::where('user_id', $userId)->where('interest_id', $data['interest_id'])->delete();
            UserInterest::where('user_id', $userId)->create($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeFromMyInterest(array $data)
    {
        $userId = $data['user_id'];
        $data = [
            'user_id' => $data['user_id'] ?? null,
            'interest_id' => $data['interest_id'] ?? null,
        ];

        try {
            UserInterest::where('user_id', $userId)->where('interest_id', $data['interest_id'])->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function sendCennectionReq(array $data)
    {
        $requestData = [
            'from_user_id' => auth()->id(),
            'to_user_id' => $data['to_user_id'] ?? null,
            'request_comment' => $data['request_comment'] ?? null,
        ];

        try {
            $checkReq = UserRequest::where('from_user_id', auth()->id())->where('to_user_id', $data['to_user_id'])->exists();
            if (!$checkReq) {
                UserRequest::create($requestData);
                // Notify the receiver
                $notificationData = [
                    'user_id' => $requestData['to_user_id'],
                    'from_user_id' => $requestData['from_user_id'],
                    'type' => 'request_sent',
                    'message' => $this->generateMessage($requestData['from_user_id'], 'request_sent'),
                    'request_comment' => $requestData['request_comment'],
                    'user_profile_image' => concatAppUrl($this->getProfileImage($requestData['from_user_id'])),
                    'is_read' => false,
                ];
                $notificationCreated = Notification::create($notificationData);
                $senderUserName = User::where('id', $requestData['from_user_id'])->first();
                $senderUserName = $senderUserName->username ?? '--';
                $receiverDeviceToken = User::where('id', $requestData['to_user_id'])->value('fcm_token');
                $pushNotificationData = [
                    'id' => $notificationCreated->id,
                    'notification_type' => 'connection_request',
                    'sender_user_id' => $requestData['from_user_id'],
                    'message_content' => $requestData['request_comment'],
                    // 'message_content' => $senderUserName . ' sent you a request',
                    'user_profile_image' => $notificationData['user_profile_image'],
                    'deviceToken' => $receiverDeviceToken,
                ];
                // Send the push notification
                if ($receiverDeviceToken) {
                    $this->notificationService->pushNotification($pushNotificationData, []);
                }
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getPendingReqs()
    {
        try {
            $user = Auth::user();

            $pendingRequests = UserRequest::with(['fromUser' => function ($query) {
                $query->with(['profileImages' => function ($query) {
                    $query->where('is_default', 1);
                }]);
            }])
                ->where(function ($query) use ($user) {
                    $query->where('to_user_id', $user->id);
                })
                ->where('request_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($request) {
                    $fromUserName = User::where('id', $request->from_user_id)->value('username');
                    return [
                        'id' => $request->id,
                        'user_id' => $request->to_user_id,
                        'from_user_id' => $request->from_user_id,
                        'from_user_name' => $fromUserName,
                        'request_comment' => $request->request_comment,
                        'message' => $fromUserName . ' sent you a request',
                        'created_at' => $request->created_at,
                        'user_profile_image' => $request->fromUser->profileImages->first() ? concatAppUrl($request->fromUser->profileImages->first()->image_name) : null,
                    ];
                });
            return $pendingRequests;
        } catch (\Exception $e) {
            \Log::error('Error fetching pending requests: ' . $e->getMessage());
            return false;
        }
    }

    public function acceptRejectReq($checkReqExists, array $requestedData)
    {
        $data = [
            'from_user_id' => $requestedData['action_by'] ?? null,
            'to_user_id' => $requestedData['action_to'] ?? null,
            'request_comment' => $requestedData['request_comment'] ?? null,
            'request_status' => $requestedData['request_status'] ?? null,
            'notification_id' => $requestedData['notification_id'] ?? null,
        ];

        try {
            $checkReqExists->update(['request_status' => $data['request_status']]);

            if ($data['request_status'] === 'accepted') {
                if ($data['notification_id']) {
                    // update my notification update
                    Notification::where('id', $data['notification_id'])->update(['is_connected' => true, 'request_comment' => null, 'type' => 'connected', 'message' => $this->generateMessage($data['to_user_id'], 'connected'), 'is_read' => 1]);
                } else {
                    Notification::where('from_user_id', $requestedData['action_to'])->where('user_id', $requestedData['action_by'])->update(['is_connected' => true, 'request_comment' => null, 'type' => 'connected', 'message' => $this->generateMessage($data['to_user_id'], 'connected'), 'is_read' => 1]);
                }
                // Notify the sender
                Notification::create([
                    'user_id' => $data['to_user_id'],
                    'from_user_id' => $data['from_user_id'],
                    'type' => 'connected',
                    'message' => $this->generateMessage($data['from_user_id'], $data['request_status']),
                    'user_profile_image' => concatAppUrl($this->getProfileImage($data['from_user_id'])),
                    'is_read' => false,
                ]);
            }
            if ($data['request_status'] === 'rejected') {
                if ($data['notification_id']) {
                    // delete my notification update
                    UserRequest::where('from_user_id', $data['from_user_id'])->where('to_user_id', $data['to_user_id'])->delete();
                    Notification::where('id', $data['notification_id'])->delete();
                } else {
                    Notification::where('from_user_id', $data['to_user_id'])->where('user_id', $data['from_user_id'])->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function generateMessage($fromUserId, $type)
    {
        $sender = User::find($fromUserId);
        switch ($type) {
            case 'accepted':
                return "$sender->username accepted your request.";
            case 'rejected':
                return "$sender->username rejected your request.";
            case 'action':
                return "You have a new action request from $sender->username.";
            case 'request_sent':
                return "$sender->username sent you a request";
            case 'connected':
                return "You are now friend with $sender->username";
            default:
                return "You have a new notification from $sender->username.";
        }
    }

    private function getProfileImage($userId)
    {
        $profileImage = UserProfileImage::where('user_id', $userId)
            ->where('is_default', '1')
            ->first();

        if ($profileImage) {
            return $profileImage->image_name;
        } else {
            return null;
        }
    }

    public function getMyConnections(int $userId, $perPage = 100000, $page = 1)
    {
        $auth_userId = $userId;
        $requests = UserRequest::where(function ($query) use ($userId) {
            $query->where('from_user_id', $userId)
                ->orWhere('to_user_id', $userId);
        })
            ->where('request_status', 'accepted')
            ->orderBy('id', 'desc') // Order by ID in descending order
            ->with(['fromUser.profileImages', 'toUser.profileImages'])
            ->paginate($perPage, ['*'], 'page', $page);

        if ($requests->isEmpty()) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        }

        $userIds = $requests->getCollection()->map(function ($request) use ($userId) {
            return $request->from_user_id == $userId ? $request->to_user_id : $request->from_user_id;
        })->unique()->toArray();

        $authuserInterestIds = UserInterest::where('user_id', $auth_userId)->pluck('interest_id')->toArray();

        $blockedUsers = UserBlock::where('blocked_by_user_id', $userId)
            ->where('blocked_status', 'blocked')
            ->pluck('blocked_user_id')
            ->toArray();

        $reportedUsers = UserReport::where('reported_by_user_id', $userId)
            ->pluck('reported_user_id')
            ->toArray();

        $connections = $requests->getCollection()->map(function ($request) use ($userId, $blockedUsers, $reportedUsers, $authuserInterestIds,$auth_userId) {
            $user = $request->from_user_id == $userId ? $request->toUser : $request->fromUser;
            if (in_array($user->id, $blockedUsers)) {
                return null;
            }
            // if (in_array($user->id, $reportedUsers)) {
            //     return null;
            // }
            $defaultImage = $user->profileImages->where('is_default', true)->value('image_name');
            $default_profile_picture = null;
            if (!is_null($defaultImage)) {
                $default_profile_picture = concatAppUrl($defaultImage);
            }
            $user_info = User::getUserInfo($user->id);
            $UserInterestIds = UserInterest::where('user_id', $user->id)->pluck('interest_id')->toArray();
            // GEt User Is Favourite
             $is_favourite = UserFavourite::where('favorited_by_user_id', $auth_userId)
                ->where('favorited_user_id', $user->id)
                ->exists() ? true : false;
            // Interest list
            $interestData = InterestMaster::whereIn('id', $UserInterestIds)->get()->map(function ($interest) use ($authuserInterestIds) {
                return [
                    'interest_name' => $interest->interest_name,
                    'interest_match' => in_array($interest->id, $authuserInterestIds),
                ];
            });

            return [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'default_profile_picture' => $default_profile_picture,
                'user_info' => $user_info,
                'user_interest' => $interestData,
                'is_favourite' => $is_favourite,
                'profile_images' => $user->profileImages->map(function ($image) {
                    return [
                        'image_id' => $image->id,
                        'image_url' => url('storage/' . $image->image_name),
                        'is_default' => boolval($image->is_default),
                    ];
                })->toArray(),
            ];
        })->filter()->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $connections,
            $requests->total(),
            $requests->perPage(),
            $requests->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }


    public function getMyFavoriteUsers(int $userId, $perPage = 100000, $page = 1)
    {
        $auth_userId = $userId;
        $favoritedUsersQuery = User::findOrFail($userId)
            ->favoritedUsers()
            ->with('favoritedUser.profileImages')
            ->orderBy('id', 'desc');

        $favoritedUsers = $favoritedUsersQuery->paginate($perPage, ['*'], 'page', $page);

        if ($favoritedUsers->isEmpty()) {
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                $perPage,
                $page,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        }

        $favoritedUserIds = $favoritedUsers->getCollection()->map(function ($userFavorite) {
            return $userFavorite->favoritedUser->id;
        })->unique()->toArray();

        $authuserInterestIds = UserInterest::where('user_id', $auth_userId)->pluck('interest_id')->toArray();

        $blockedUsers = UserBlock::where('blocked_by_user_id', $userId)
            ->where('blocked_status', 'blocked')
            ->pluck('blocked_user_id')
            ->toArray();

        $removedUsers = RemovedUser::where('removed_by_user_id', $userId)
            ->pluck('removed_user_id')
            ->toArray();

        $reportedUsers = UserReport::where('reported_by_user_id', $userId)
            ->pluck('reported_user_id')
            ->toArray();
        $authUserId = auth()->id();

        $filteredFavoriteUsers = $favoritedUsers->getCollection()->map(function ($userFavorite) use ($blockedUsers, $reportedUsers, $removedUsers, $authUserId,$authuserInterestIds) {
            $user = $userFavorite->favoritedUser;

            if (in_array($user->id, $blockedUsers)) {
                return null;
            }
            if (in_array($user->id, $reportedUsers)) {
                return null;
            }
            if (in_array($user->id, $removedUsers)) {
                return null;
            }

            $isConnected = UserRequest::where(function ($query) use ($authUserId, $user) {
                $query->where('from_user_id', $authUserId)
                    ->where('to_user_id', $user->id)
                    ->where('request_status', 'accepted');
            })->orWhere(function ($query) use ($authUserId, $user) {
                $query->where('from_user_id', $user->id)
                    ->where('to_user_id', $authUserId)
                    ->where('request_status', 'accepted');
            })->exists();

            $defaultImage = $user->profileImages->where('is_default', true)->value('image_name');
            $default_profile_picture = null;
            if (!is_null($defaultImage)) {
                $default_profile_picture = concatAppUrl($defaultImage);
            }

            $user_info = User::getUserInfo($user->id);
            $UserInterestIds = UserInterest::where('user_id', $user->id)->pluck('interest_id')->toArray();

              // GEt User Is Favourite
             $is_favourite = UserFavourite::where('favorited_by_user_id', $authUserId)
                ->where('favorited_user_id', $user->id)
                ->exists() ? true : false;

            // Interest list
            $interestData = InterestMaster::whereIn('id', $UserInterestIds)->get()->map(function ($interest) use ($authuserInterestIds) {
                return [
                    'interest_name' => $interest->interest_name,
                    'interest_match' => in_array($interest->id, $authuserInterestIds),
                ];
            });
            return [
                'id' => $user->id,
                'name' => $user->username,
                'email' => $user->email,
                'is_connected' => $isConnected,
                'default_profile_picture' => $default_profile_picture,
                'user_info' => $user_info,
                'user_interest' => $interestData,
                'is_favourite' => $is_favourite,
                'profile_images' => $user->profileImages->map(function ($image) {
                    return [
                        'image_id' => $image->id,
                        'image_url' => concatAppUrl($image->image_name),
                        'is_default' => boolval($image->is_default),
                    ];
                })->toArray(),
            ];
        })->filter()->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredFavoriteUsers,
            $favoritedUsers->total(),
            $favoritedUsers->perPage(),
            $favoritedUsers->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function deleteConnection($userId, $friendId)
    {
        $connection = UserRequest::where(function ($query) use ($userId, $friendId) {
            $query->where('from_user_id', $userId)
                ->where('to_user_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('from_user_id', $friendId)
                ->where('to_user_id', $userId);
        })->where('request_status', 'accepted')->first();
        if (!$connection) {
            return false;
        }
        UserMessages::where(function ($query) use ($userId, $friendId) {
            $query->where('sender_user_id', $userId)
                ->where('receiver_user_id', $friendId);
        })->orWhere(function ($query) use ($userId, $friendId) {
            $query->where('sender_user_id', $friendId)
                ->where('receiver_user_id', $userId);
        })->delete();

        return $connection->delete();
    }

    public function removeFavouriteUser($requestData)
    {
        return UserFavourite::where('favorited_by_user_id', auth()->id())
            ->where('favorited_user_id', $requestData['favorited_user_id'])
            ->delete();
    }

    public function storeUserPolicy($updateData)
    {
        return UserDetail::where('user_id', auth()->id())->update($updateData);
    }

    public function userPreferences()
    {
        $column = [
            'user_details.location',
            'user_details.location_latitude',
            'user_details.location_longitude',
            'user_details.is_distance_preference',
            'user_details.distance_preference',
            'user_details.dob',
            'user_details.is_age_preference',
            'user_details.from_age_preference',
            'user_details.to_age_preference',
            'user_details.is_mutual_interest_preference',
            'user_details.min_mutual_interest',
            'user_details.gender_preference',
            'user_details.is_display_in_search',
            'user_details.is_display_in_recommendation',
        ];
        $data = User::leftJoin('user_details', 'user_details.user_id', 'users.id')->where('users.id', auth()->id())->select($column)->get();
        $defaultValues = config('default-values.default_values');
        foreach ($data as $item) {
            if ($item->dob) {
                $dob = new \DateTime($item->dob);
                $item->dob = $dob->format('m-d-Y');
                $today = new \DateTime('today');
                $age = $dob->diff($today)->y;
                $item->age = $age;
            } else {
                $item->age = null;
            }
            $item->max_distance_pref = $defaultValues['max_distance_pref'];
            $item->min_age_preference = $defaultValues['min_age_preference'];
            $item->max_age_preference = $defaultValues['max_age_preference'];
            $item->max_mutual_interest_pref = $defaultValues['max_mutual_interest_pref'];

            $item->is_distance_preference = $item->is_distance_preference == 1 ? true : false;
            $item->is_age_preference = $item->is_age_preference == 1 ? true : false;
            $item->is_mutual_interest_preference = $item->is_mutual_interest_preference == 1 ? true : false;
            $item->is_display_in_search = $item->is_display_in_search == 1 ? true : false;
            $item->is_display_in_recommendation = $item->is_display_in_recommendation == 1 ? true : false;
        }
        return $data->toArray();
    }

    public function storeUserPreferences(array $data)
    {
        $userId = auth()->id();
        $userDetails = UserDetail::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
        return $userDetails;
    }

    public function defaultUserImage($data)
    {
        $userId = auth()->id();
        UserProfileImage::where('user_id', $userId)->update(['is_default' => false]);
        $updated = UserProfileImage::where('id', $data['image_id'])
            ->where('user_id', $userId)
            ->update(['is_default' => true]);
        return $updated > 0;
    }

    public function removeUser($removedUserId)
    {
        $authId = auth()->id();
        $data = [
            'removed_user_id' => $removedUserId,
            'removed_by_user_id' => $authId,
        ];
        $user = User::where('id', $removedUserId)->first();
        if ($user->hasRole('App User')) {
            RemovedUser::create($data);
            return true;
        }
        return false;
    }
}
