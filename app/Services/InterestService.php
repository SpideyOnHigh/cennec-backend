<?php

namespace App\Services;

use App\Models\InterestMaster;
use App\Models\InvitationCodeMaster;
use App\Models\QuestionMaster;
use App\Models\RemovedUser;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserDetail;
use App\Models\UserFavourite;
use App\Models\UserInterest;
use App\Models\UserProfileImage;
use App\Models\UserQuestionAnswer;
use App\Models\UserRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class InterestService
{
    public function fetchInterestList()
    {
        $interests = InterestMaster::all();
        $userInterests = UserInterest::where('user_id', Auth::id())->pluck('interest_id')->toArray();

        $interests->map(function ($interest) use ($userInterests) {
            $interest->is_interested_added = in_array($interest->id, $userInterests);
            return $interest;
        });

        return $interests;
    }

    // public function fetchInterestRelatedUser($interestId, $columns)
    // {
    //     $authUserId = auth()->id();
    //     $authUserInterests = UserInterest::where('user_id', $authUserId)
    //         ->pluck('interest_id')
    //         ->toArray();

    //     $blockedUsers = UserBlock::where('blocked_by_user_id', $authUserId)
    //         ->where('blocked_status', 'blocked')
    //         ->pluck('blocked_user_id')
    //         ->toArray();

    //     $removedUsers = RemovedUser::where('removed_by_user_id', $authUserId)
    //         ->pluck('removed_user_id')
    //         ->toArray();

    //     $users = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
    //         ->whereIn('users.id', function ($query) use ($interestId) {
    //             $query->select('user_id')
    //                 ->from('user_interests')
    //                 ->where('interest_id', $interestId);
    //         })
    //         ->whereNotIn('users.id', array_merge([$authUserId], $blockedUsers, $removedUsers))
    //         ->select($columns)
    //         ->get();

    //     $userIds = $users->pluck('user_id')->toArray();
    //     $profileImages = UserProfileImage::whereIn('user_id', $userIds)->get()->groupBy('user_id');
    //     $favourites = UserFavourite::where('favorited_by_user_id', $authUserId)
    //         ->pluck('favorited_user_id')
    //         ->toArray();

    //     $acceptedConnections = UserRequest::where('request_status', 'accepted')
    //         ->where(function ($query) use ($authUserId) {
    //             $query->where('from_user_id', $authUserId)
    //                 ->orWhere('to_user_id', $authUserId);
    //         })
    //         ->pluck('from_user_id', 'to_user_id')
    //         ->toArray();

    //     $connections = [];
    //     foreach ($acceptedConnections as $fromUserId => $toUserId) {
    //         $connections[$fromUserId] = true;
    //         $connections[$toUserId] = true;
    //     }


    //     foreach ($users as $user) {
    //         if (isset($user->dob)) {
    //             $user->dob = \Carbon\Carbon::parse($user->dob)->format('m-d-Y');
    //         }
    //         $userInterests = UserInterest::where('user_id', $user->user_id)
    //             ->pluck('interest_id')
    //             ->toArray();

    //         $mutualInterests = array_intersect($authUserInterests, $userInterests);
    //         $user->mutual_interests = count($mutualInterests);
    //         $user->is_favourite = in_array($user->user_id, $favourites);
    //         $user->is_connected = isset($connections[$user->user_id]);
    //         if (isset($profileImages[$user->user_id])) {
    //             $defaultImage = $profileImages[$user->user_id]->where('is_default', true)->value('image_name');
    //             $user->default_profile_picture = null;
    //             if (!is_null($defaultImage)) {
    //                 $user->default_profile_picture = concatAppUrl($defaultImage);
    //             }
    //             $user->profile_images = $profileImages[$user->user_id]->map(function ($image) {
    //                 return [
    //                     'image_id' => $image->id,
    //                     'image_url' => concatAppUrl($image->image_name),
    //                     'is_default' => boolval($image->is_default),
    //                 ];
    //             });
    //         } else {
    //             $user->profile_images = [];
    //         }
    //     }
    //     return $users;
    // }

    public function fetchInterestRelatedUser($interestId, $columns, $perPage, $page)
    {
        $authUserId = auth()->id();
        $authUserInterests = UserInterest::where('user_id', $authUserId)
            ->pluck('interest_id')
            ->toArray();

        $blockedUsers = UserBlock::where('blocked_by_user_id', $authUserId)
            ->where('blocked_status', 'blocked')
            ->pluck('blocked_user_id')
            ->toArray();

        $removedUsers = RemovedUser::where('removed_by_user_id', $authUserId)
            ->pluck('removed_user_id')
            ->toArray();

        $query = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
            ->whereIn('users.id', function ($query) use ($interestId) {
                $query->select('user_id')
                    ->from('user_interests')
                    ->where('interest_id', $interestId);
            })
            ->whereNotIn('users.id', array_merge([$authUserId], $blockedUsers, $removedUsers))
            ->select($columns);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        $userIds = $users->pluck('user_id')->toArray();
        $profileImages = UserProfileImage::whereIn('user_id', $userIds)->get()->groupBy('user_id');
        $favourites = UserFavourite::where('favorited_by_user_id', $authUserId)
            ->pluck('favorited_user_id')
            ->toArray();

        $acceptedConnections = UserRequest::where('request_status', 'accepted')
            ->where(function ($query) use ($authUserId) {
                $query->where('from_user_id', $authUserId)
                    ->orWhere('to_user_id', $authUserId);
            })
            ->pluck('from_user_id', 'to_user_id')
            ->toArray();

        $connections = [];
        foreach ($acceptedConnections as $fromUserId => $toUserId) {
            $connections[$fromUserId] = true;
            $connections[$toUserId] = true;
        }

        foreach ($users as $user) {
            if (isset($user->dob)) {
                $user->dob = \Carbon\Carbon::parse($user->dob)->format('m-d-Y');
            }
            $userInterests = UserInterest::where('user_id', $user->user_id)
                ->pluck('interest_id')
                ->toArray();

            $mutualInterests = array_intersect($authUserInterests, $userInterests);
            $user->mutual_interests = count($mutualInterests);
            $user->is_favourite = in_array($user->user_id, $favourites);
            $user->is_connected = isset($connections[$user->user_id]);
            if (isset($profileImages[$user->user_id])) {
                $defaultImage = $profileImages[$user->user_id]->where('is_default', true)->value('image_name');
                $user->default_profile_picture = null;
                if (!is_null($defaultImage)) {
                    $user->default_profile_picture = concatAppUrl($defaultImage);
                }
                $user->profile_images = $profileImages[$user->user_id]->map(function ($image) {
                    return [
                        'image_id' => $image->id,
                        'image_url' => concatAppUrl($image->image_name),
                        'is_default' => boolval($image->is_default),
                    ];
                });
            } else {
                $user->profile_images = [];
            }
        }

        return $users;
    }

    public function isInterestExistsWithUser($interestId)
    {
        $exists = UserInterest::where('interest_id', $interestId)->where('user_id', Auth::id())
            ->exists();
        return $exists;
    }

    public function relatedInterest($interestId)
    {
        $interest = InterestMaster::where('id', $interestId)->first();
        if (!$interest) {
            return [];
        }

        $relatedInterests = InterestMaster::where('interest_category_id', $interest->interest_category_id)
            ->select('id', 'interest_name', 'interest_color')
            ->whereNot('id', $interestId)
            ->get()
            ->toArray();

        $userInterests = UserInterest::where('user_id', Auth::id())
            ->pluck('interest_id')
            ->toArray();

        foreach ($relatedInterests as &$relatedInterest) {
            $relatedInterest['is_interested_added'] = in_array($relatedInterest['id'], $userInterests);
        }

        return $relatedInterests;
    }

    // public function fetchRecomUser($columns)
    // {
    //     $authUser = auth()->user();
    //     $authUserId = $authUser->id;
    //     $authUserDetails = UserDetail::where('user_id', $authUserId)->first();
    //     $interestIds = UserInterest::where('user_id', $authUserId)->pluck('interest_id')->toArray();

    //     $authUserLocation = $authUserDetails->location;
    //     $authUserLatitude = $authUserDetails->location_latitude;
    //     $authUserLongitude = $authUserDetails->location_longitude;

    //     $invitationCodeId = User::where('id', $authUserId)->pluck('invitation_code_id')->first();
    //     $invitationCode = InvitationCodeMaster::where('id', $invitationCodeId)->pluck('code')->first();

    //     $blockedUsers = UserBlock::where('blocked_by_user_id', $authUserId)
    //         ->where('blocked_status', 'blocked')
    //         ->pluck('blocked_user_id')->toArray();

    //     $removedUsers = RemovedUser::where('removed_by_user_id', $authUserId)
    //         ->pluck('removed_user_id')
    //         ->toArray();
    //     $excludedUsers = array_merge([$authUserId], $blockedUsers);
    //     $usersQuery = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
    //         ->whereNotIn('users.id', $excludedUsers)
    //         ->whereNotIn('users.id', $removedUsers)
    //         ->where('user_details.is_display_in_recommendation', true)
    //         ->whereHas('roles', function ($query) {
    //             $query->where('name', 'App User');
    //         });

    //     $this->applyUserPreferences($usersQuery, $authUserDetails, $interestIds, $invitationCode, $authUserLocation, $authUserLatitude, $authUserLongitude);

    //     $usersQuery = $usersQuery->get();
    //     return $this->prepareUserData($usersQuery, $authUserId);
    // }

    public function fetchRecomUser($columns, $perPage, $page)
    {
        $authUser = auth()->user();
        $authUserId = $authUser->id;
        $authUserDetails = UserDetail::where('user_id', $authUserId)->first();
        $interestIds = UserInterest::where('user_id', $authUserId)->pluck('interest_id')->toArray();

        $authUserLocation = $authUserDetails->location;
        $authUserLatitude = $authUserDetails->location_latitude;
        $authUserLongitude = $authUserDetails->location_longitude;

        $invitationCodeId = User::where('id', $authUserId)->pluck('invitation_code_id')->first();
        $invitationCode = InvitationCodeMaster::where('id', $invitationCodeId)->pluck('code')->first();

        $blockedUsers = UserBlock::where('blocked_by_user_id', $authUserId)
            ->where('blocked_status', 'blocked')
            ->pluck('blocked_user_id')->toArray();

        $removedUsers = RemovedUser::where('removed_by_user_id', $authUserId)
            ->pluck('removed_user_id')
            ->toArray();
        $excludedUsers = array_merge([$authUserId], $blockedUsers);
        $usersQuery = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
            ->whereNotIn('users.id', $excludedUsers)
            ->whereNotIn('users.id', $removedUsers)
            ->where('user_details.is_display_in_recommendation', true)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'App User');
            });

        $this->applyUserPreferences($usersQuery, $authUserDetails, $interestIds, $invitationCode, $authUserLocation, $authUserLatitude, $authUserLongitude);

        // $usersQuery = $usersQuery->get();
        $usersQuery = $usersQuery->paginate($perPage, $columns, 'page', $page);

        return $this->prepareUserData($usersQuery, $authUserId);
    }

    private function applyUserPreferences($query, $authUserDetails, $interestIds, $invitationCode, $authUserLocation, $authUserLatitude, $authUserLongitude)
    {
        if ($authUserDetails->gender_preference) {
            $query->where('user_details.gender', $authUserDetails->gender_preference);
        }
        if ($authUserDetails->is_age_preference) {
            $currentDate = now();
            $minBirthDate = $currentDate->subYears($authUserDetails->from_age_preference)->format('Y-m-d');
            $maxBirthDate = $currentDate->subYears($authUserDetails->to_age_preference)->format('Y-m-d');
            $query->whereBetween('user_details.dob', [$maxBirthDate, $minBirthDate]);
        }
        if ($authUserDetails->is_mutual_interest_preference) {
            $minMutualInterest = $authUserDetails->min_mutual_interest;
            $authUserInterests = UserInterest::where('user_id', auth()->id())->pluck('interest_id');
            $query->leftJoin('user_interests as ui', 'user_details.id', '=', 'ui.user_id')
                ->whereIn('ui.interest_id', $authUserInterests)
                ->groupBy('user_details.id') // Group by user_details.id
                ->havingRaw('COUNT(ui.interest_id) >= ?', [$minMutualInterest])
                ->select('user_details.*', \DB::raw('COUNT(ui.interest_id) as mutual_interest_count')); // Only select necessary fields
        }
        if ($authUserDetails->is_distance_preference) {
            $distancePreference = $authUserDetails->distance_preference;

            $query->selectRaw(
                "user_details.*, 
                                (3959 * acos(cos(radians(?)) * cos(radians(location_latitude)) 
                                * cos(radians(location_longitude) - radians(?)) 
                                + sin(radians(?)) * sin(radians(location_latitude)))) AS distance",
                [$authUserLatitude, $authUserLongitude, $authUserLatitude]
            )
                ->having("distance", "<=", $distancePreference)
                ->orderBy("distance");
        }
        return $query;
    }


    private function prepareUserData($usersQuery, $authUserId)
    {
        // Fetch related data for users
        $userIds = $usersQuery->pluck('user_id')->toArray();
        $profileImages = UserProfileImage::whereIn('user_id', $userIds)->get()->groupBy('user_id');
        $favourites = UserFavourite::where('favorited_by_user_id', $authUserId)->pluck('favorited_user_id')->toArray();

        // Gather connection information
        $connections = UserRequest::where('request_status', 'accepted')
            ->where(function ($query) use ($authUserId) {
                $query->where('from_user_id', $authUserId)
                    ->orWhere('to_user_id', $authUserId);
            })
            ->pluck('from_user_id', 'to_user_id')->toArray();

        foreach ($usersQuery as $user) {
            if (isset($user->dob)) {
                $user->dob = \Carbon\Carbon::parse($user->dob)->format('m-d-Y');
            }

            // Set interests
            $user->user_interests = UserInterest::where('user_id', $user->user_id)
                ->leftJoin('interest_masters', 'user_interests.interest_id', '=', 'interest_masters.id')
                ->select('user_interests.user_id', 'interest_masters.id as interest_id', 'interest_masters.interest_name', 'interest_masters.interest_color')
                ->get()->map(function ($interest) {
                    return [
                        'interest_id' => $interest->interest_id,
                        'interest_name' => $interest->interest_name,
                        'interest_color' => $interest->interest_color,
                    ];
                });

            // Favourite and connection status
            $user->is_favourite = in_array($user->user_id, $favourites);
            $user->is_connected = isset($connections[$user->user_id]);

            // Profile images
            $user->profile_images = [];
            if (isset($profileImages[$user->user_id])) {
                $defaultImage = $profileImages[$user->user_id]->where('is_default', true)->value('image_name');
                if (!is_null($defaultImage)) {
                    $user->default_profile_picture = concatAppUrl($defaultImage);
                }
                $user->profile_images = $profileImages[$user->user_id]->map(function ($image) {
                    return [
                        'image_id' => $image->id,
                        'image_url' => concatAppUrl($image->image_name),
                        'is_default' => boolval($image->is_default),
                    ];
                });
            }

            // User question answers
            $userAnswers = UserQuestionAnswer::where('user_id', $user->user_id)->get(['question_id', 'answer']);
            $answersMap = $userAnswers->pluck('answer', 'question_id');
            $questions = QuestionMaster::where('question_status', 1)->orderBy('question_orders')->get(['id', 'question']);
            $user->user_que_ans = $questions->map(function ($question) use ($answersMap) {
                return [
                    'question_id' => $question->id,
                    'question' => $question->question,
                    'answer' => $answersMap->get($question->id, null),
                ];
            });
        }

        return $usersQuery;
    }


    //pagination code of fetchRecomUser
    // public function fetchRecomUser($columns, $perPage = 15, $page = 1)
    // {
    //     $perPage = 100000;
    //     $authUser = auth()->user();
    //     $authUserId = $authUser->id;

    //     $interestIds = UserInterest::where('user_id', $authUserId)
    //         ->pluck('interest_id')
    //         ->toArray();

    //     $authUserLocation = UserDetail::where('user_id', $authUserId)
    //         ->pluck('location')
    //         ->first();

    //     $invitationCodeId = User::where('id', $authUserId)
    //         ->pluck('invitation_code_id')
    //         ->first();

    //     $invitationCode = InvitationCodeMaster::where('id', $invitationCodeId)
    //         ->pluck('code')
    //         ->first();

    //     $blockedUsers = UserBlock::where('blocked_by_user_id', $authUserId)
    //         ->where('blocked_status', 'blocked')
    //         ->pluck('blocked_user_id')
    //         ->toArray();

    //     $query = User::leftJoin('user_details', 'user_details.user_id', 'users.id')
    //         ->leftJoin('invitation_code_masters', 'invitation_code_masters.id', 'users.invitation_code_id')
    //         ->where(function ($query) use ($interestIds, $authUserLocation, $invitationCode) {
    //             if (!empty($interestIds)) {
    //                 $query->orWhereIn('users.id', function ($subQuery) use ($interestIds) {
    //                     $subQuery->select('user_id')
    //                         ->from('user_interests')
    //                         ->whereIn('interest_id', $interestIds);
    //                 });
    //             }
    //             if ($authUserLocation) {
    //                 $query->orWhere('user_details.location', $authUserLocation);
    //             }
    //             if ($invitationCode) {
    //                 $query->orWhere('invitation_code_masters.code', $invitationCode);
    //             }
    //         })
    //         ->whereNotIn('users.id', array_merge([$authUserId], $blockedUsers))
    //         ->whereHas('roles', function ($query) {
    //             $query->where('name', 'App User');
    //         })
    //         ->select($columns);

    //     $users = $query->paginate($perPage, ['*'], 'page', $page);

    //     $userIds = $users->pluck('user_id')->toArray();
    //     $profileImages = UserProfileImage::whereIn('user_id', $userIds)->get()->groupBy('user_id');
    //     $favourites = UserFavourite::where('favorited_by_user_id', $authUserId)
    //         ->pluck('favorited_user_id')
    //         ->toArray();

    //     $acceptedConnections = UserRequest::where('request_status', 'accepted')
    //         ->where(function ($query) use ($authUserId) {
    //             $query->where('from_user_id', $authUserId)
    //                 ->orWhere('to_user_id', $authUserId);
    //         })
    //         ->pluck('from_user_id', 'to_user_id')
    //         ->toArray();

    //     $connections = [];
    //     foreach ($acceptedConnections as $fromUserId => $toUserId) {
    //         $connections[$fromUserId] = true;
    //         $connections[$toUserId] = true;
    //     }

    //     $userQuestions = UserQuestionAnswer::all()->groupBy('user_id');

    //     $userInterests = UserInterest::leftJoin('interest_masters', 'user_interests.interest_id', '=', 'interest_masters.id')
    //         ->select('user_interests.user_id', 'interest_masters.id as interest_id', 'interest_masters.interest_name', 'interest_masters.interest_color')
    //         ->get()
    //         ->groupBy('user_id');

    //     foreach ($users as $user) {
    //         if (isset($user->dob)) {
    //             $user->dob = \Carbon\Carbon::parse($user->dob)->format('m-d-Y');
    //         }
    //         $userInterestsData = $userInterests->get($user->user_id, collect())->map(function ($interest) {
    //             return [
    //                 'interest_id' => $interest->interest_id,
    //                 'interest_name' => $interest->interest_name,
    //                 'interest_color' => $interest->interest_color,
    //             ];
    //         });

    //         $user->is_favourite = in_array($user->user_id, $favourites);
    //         $user->is_connected = isset($connections[$user->user_id]);
    //         if (isset($profileImages[$user->user_id])) {
    //             $user->profile_images = $profileImages[$user->user_id]->map(function ($image) {
    //                 return [
    //                     'image_id' => $image->id,
    //                     'image_url' => concatAppUrl($image->image_name),
    //                 ];
    //             });
    //         } else {
    //             $user->profile_images = [];
    //         }
    //         $user->user_que_ans = $userQuestions->get($user->user_id, collect())->map(function ($answer) {
    //             return [
    //                 'question_id' => $answer->question_id,
    //                 'question' => $answer->question,
    //                 'answer' => $answer->answer,
    //             ];
    //         });
    //         $user->user_interests = $userInterestsData;
    //     }
    //     return $users;
    // }
}
