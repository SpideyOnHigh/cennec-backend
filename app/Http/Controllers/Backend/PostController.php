<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\InterestMaster;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserInterest;
use App\Models\UserPosts;
use App\Models\UserProfileImage;
use App\Models\UserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function addUserPost(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activity' => 'nullable',
                'location' => 'nullable',
                'meet_at' => 'nullable',
                'meet_with' => 'nullable',
                'discussion_topic' => 'nullable',
                'description' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'data' => '',
                    'count' => '',
                ], 422);
            }

            //Add user post
            $user_post =  UserPosts::addUserPost($request);

            if ($user_post) {
                return response()->json([
                    'code' => 200,
                    'message' => 'Post added successfully',
                    'data' => $user_post,
                    'count' => 1,
                ]);
            } else {
                return response()->json([
                    'code' => 500,
                    'message' => 'Failed to add post',
                    'data' => '',
                    'count' => '',
                ]);
            }
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => '',
                'count' => '',
            ]);
        }
    }


    public function getSimilatPost(Request $request)
    {
        try {
            $authUser = Auth::user();
            $userInterestIds = UserInterest::where('user_id', $authUser->id)->pluck('interest_id')->toArray();
            $searchText = trim($request->input('description', ''));

            $otherPosts = UserPosts::where('user_id', '!=', $authUser->id)
                ->when(!empty($searchText), function ($query) use ($searchText) {
                    $query->where(function ($q) use ($searchText) {
                        $q->whereRaw("MATCH(description) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchText])
                            ->orWhere('description', 'LIKE', '%' . $searchText . '%')
                            ->orWhere('description', 'LIKE', '%' . str_replace(' ', '', $searchText) . '%');
                    });
                })
                ->with('user')
                ->get();

            $result = $otherPosts->map(function ($post) use ($userInterestIds, $authUser, $searchText) {
                $postUser = $post->user;
                $user_profile = UserProfileImage::where('user_id', $postUser->id)->first();
                $postUserInterestIds = UserInterest::where('user_id', $post->user_id)->pluck('interest_id')->toArray();

                $matchPercentage = 0;
                if (!empty($searchText)) {
                    similar_text(strtolower($post->description), strtolower($searchText), $matchPercentage);
                }

                $interestData = InterestMaster::whereIn('id', $postUserInterestIds)->get()->map(function ($interest) use ($userInterestIds) {
                    return [
                        'interest_name' => $interest->interest_name,
                        'interest_match' => in_array($interest->id, $userInterestIds),
                    ];
                });

                $mutualConnectionCount = PostController::getMutualConnectionCount($authUser->id, $postUser->id);
                $is_friend = PostController::isFriend($authUser->id, $postUser->id);
                $user_info = User::getUserInfo($postUser->id);

                return [
                    'id' => $post->id,
                    'activity' => $post->activity,
                    'location' => $post->location,
                    'meet_at' => $post->meet_at,
                    'meet_with' => $post->meet_with,
                    'discussion_topic' => $post->discussion_topic,
                    'description' => $post->description,
                    'user_id' => $postUser->id,
                    'user_name' => $postUser->name ?? '',
                    'mutual_connection' => $mutualConnectionCount,
                    'is_friend' => $is_friend,
                    'match_percentage' => round($matchPercentage),
                    'user_image' => $user_profile ? $user_profile->image_name : null,
                    'user_interest' => $interestData,
                    'user_info' => $user_info,
                ];
            })->sortByDesc('match_percentage')->values();

            $similarPosts = $result->filter(fn($post) => $post['match_percentage'] >= 40)->values();

            $skip = (int) $request->input('skip', 0);
            $take = (int) $request->input('take', 10);
            $paginatedPosts = $similarPosts->slice($skip, $take)->values();
            $total = $similarPosts->count();
            $currentPage = $take > 0 ? intval(floor($skip / $take) + 1) : 1;

            return response()->json([
                'code' => 200,
                'message' => 'Post List',
                'data' => $paginatedPosts,
                'count' => $paginatedPosts->count(),
                'total' => $total,
                'current_page' => $currentPage,
                'per_page' => $take,
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => '',
                'count' => '',
            ]);
        }
    }





    public function getInterestMatchedPosts(Request $request)
    {
        try {
            $authUser = Auth::user();
            $userInterestIds = UserInterest::where('user_id', $authUser->id)->pluck('interest_id')->toArray();
            $searchText = trim($request->input('description', ''));

            $otherPosts = UserPosts::where('user_id', '!=', $authUser->id)
                ->when(!empty($searchText), function ($query) use ($searchText) {
                    $query->where(function ($q) use ($searchText) {
                        $q->whereRaw("MATCH(description) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchText])
                            ->orWhere('description', 'LIKE', '%' . $searchText . '%')
                            ->orWhere('description', 'LIKE', '%' . str_replace(' ', '', $searchText) . '%');
                    });
                })
                ->with('user')
                ->get();

            $result = $otherPosts->map(function ($post) use ($userInterestIds, $authUser, $searchText) {
                $postUser = $post->user;
                $user_profile = UserProfileImage::where('user_id', $postUser->id)->first();
                $postUserInterestIds = UserInterest::where('user_id', $post->user_id)->pluck('interest_id')->toArray();

                $matchPercentage = 0;
                if (!empty($searchText)) {
                    similar_text(strtolower($post->description), strtolower($searchText), $matchPercentage);
                }

                $interestData = InterestMaster::whereIn('id', $postUserInterestIds)->get()->map(function ($interest) use ($userInterestIds) {
                    return [
                        'interest_name' => $interest->interest_name,
                        'interest_match' => in_array($interest->id, $userInterestIds),
                    ];
                });

                $mutualConnectionCount = PostController::getMutualConnectionCount($authUser->id, $postUser->id);
                $is_friend = PostController::isFriend($authUser->id, $postUser->id);
                $user_info = User::getUserInfo($postUser->id);

                return [
                    'id' => $post->id,
                    'activity' => $post->activity,
                    'location' => $post->location,
                    'meet_at' => $post->meet_at,
                    'meet_with' => $post->meet_with,
                    'discussion_topic' => $post->discussion_topic,
                    'description' => $post->description,
                    'user_id' => $postUser->id,
                    'user_name' => $postUser->name ?? '',
                    'mutual_connection' => $mutualConnectionCount,
                    'is_friend' => $is_friend,
                    'match_percentage' => round($matchPercentage),
                    'user_image' => $user_profile ? $user_profile->image_name : null,
                    'user_interest' => $interestData,
                    'user_info' => $user_info,
                ];
            })->sortByDesc('match_percentage')->values();

            $similarPosts = $result->filter(fn($post) => $post['match_percentage'] >= 40)->values();
            $interestMatches = $result->filter(fn($post) => $post['match_percentage'] >= 20 && $post['match_percentage'] < 40)->values();
            $existingPostIds = array_merge(
                $similarPosts->pluck('id')->toArray(),
                $interestMatches->pluck('id')->toArray()
            );

            $interestOnlyPosts = UserPosts::where('user_id', '!=', $authUser->id)
                ->whereNotIn('id', $existingPostIds)
                ->with('user')
                ->get();

            $interestPostsArray = [];

            foreach ($interestOnlyPosts as $post) {
                $postUser = $post->user;
                $user_profile = UserProfileImage::where('user_id', $postUser->id)->first();
                $postUserInterestIds = UserInterest::where('user_id', $post->user_id)->pluck('interest_id')->toArray();
                $commonInterests = array_intersect($userInterestIds, $postUserInterestIds);

                if (count($commonInterests) > 0) {
                    $interestData = InterestMaster::whereIn('id', $postUserInterestIds)->get()->map(function ($interest) use ($userInterestIds) {
                        return [
                            'interest_name' => $interest->interest_name,
                            'interest_match' => in_array($interest->id, $userInterestIds),
                        ];
                    });

                    $mutualConnectionCount = PostController::getMutualConnectionCount($authUser->id, $postUser->id);
                    $is_friend = PostController::isFriend($authUser->id, $postUser->id);
                    $user_info = User::getUserInfo($postUser->id);

                    $interestPostsArray[] = [
                        'id' => $post->id,
                        'activity' => $post->activity,
                        'location' => $post->location,
                        'meet_at' => $post->meet_at,
                        'meet_with' => $post->meet_with,
                        'discussion_topic' => $post->discussion_topic,
                        'description' => $post->description,
                        'user_id' => $postUser->id,
                        'user_name' => $postUser->name ?? '',
                        'mutual_connection' => $mutualConnectionCount,
                        'is_friend' => $is_friend,
                        'match_percentage' => 0,
                        'user_image' => $user_profile ? $user_profile->image_name : null,
                        'user_interest' => $interestData,
                        'common_interest_count' => count($commonInterests),
                        'user_info' => $user_info,
                    ];
                }
            }

            usort($interestPostsArray, fn($a, $b) => $b['common_interest_count'] <=> $a['common_interest_count']);

            foreach ($interestPostsArray as $postData) {
                unset($postData['common_interest_count']);
                $interestMatches->push($postData);
            }

            $skip = (int) $request->input('skip', 0);
            $take = (int) $request->input('take', 10);
            $total = $interestMatches->count();
            $paginatedPosts = $interestMatches->slice($skip, $take)->values();
            $currentPage = $take > 0 ? intval(floor($skip / $take) + 1) : 1;

            return response()->json([
                'code' => 200,
                'message' => 'Post List',
                'data' => $paginatedPosts,
                'count' => $paginatedPosts->count(),
                'total' => $total,
                'current_page' => $currentPage,
                'per_page' => $take,
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => $e->getMessage(),
                'data' => '',
                'count' => '',
            ]);
        }
    }

    function getMutualConnectionCount($userId1, $userId2)
    {
        // Step 1: Get connections for user 1
        $user1Connections = UserRequest::where(function ($q) use ($userId1) {
            $q->where('from_user_id', $userId1)
                ->orWhere('to_user_id', $userId1);
        })
            ->where('request_status', 'accepted')
            ->get()
            ->map(function ($item) use ($userId1) {
                return $item->from_user_id == $userId1 ? $item->to_user_id : $item->from_user_id;
            })->unique()->toArray();

        // Step 2: Get connections for user 2
        $user2Connections = UserRequest::where(function ($q) use ($userId2) {
            $q->where('from_user_id', $userId2)
                ->orWhere('to_user_id', $userId2);
        })
            ->where('request_status', 'accepted')
            ->get()
            ->map(function ($item) use ($userId2) {
                return $item->from_user_id == $userId2 ? $item->to_user_id : $item->from_user_id;
            })->unique()->toArray();

        // Step 3: Get mutuals
        $mutuals = array_intersect($user1Connections, $user2Connections);

        return count($mutuals);
    }

    public function isFriend($userId1, $userId2)
    {
        return UserRequest::where(function ($q) use ($userId1, $userId2) {
            $q->where('from_user_id', $userId1)->where('to_user_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('from_user_id', $userId2)->where('to_user_id', $userId1);
        })
            ->where('request_status', 'accepted')
            ->exists();
    }

    public function getAllPosts(Request $request)
    {
        try {
            $authUser = Auth::user();

            $authInterestIds = UserInterest::where('user_id', $authUser->id)
                ->pluck('interest_id')
                ->toArray();

            // Request inputs
            $skip = $request->skip ?? 0;
            $take = $request->take ?? 10;
            $orderBy = $request->order_by ?? 'interest_match_count';
            $sortDirection = $request->sort ?? 'desc';

            // All posts (excluding auth user)
            $allPosts = UserPosts::where('user_id', '!=', $authUser->id)
                ->with('user')
                ->get();

            // Filter and process only posts that have interest matches
            $filtered = $allPosts->filter(function ($post) use ($authInterestIds) {
                $postUserInterests = UserInterest::where('user_id', $post->user_id)
                    ->pluck('interest_id')
                    ->toArray();

                $matchedInterests = array_intersect($authInterestIds, $postUserInterests);


                return count($matchedInterests) > 0;
            })->values();

            // Now map only filtered posts
            $processed = $filtered->map(function ($post) use ($authInterestIds, $authUser) {
                $postUserInterests = UserInterest::where('user_id', $post->user_id)
                    ->pluck('interest_id')
                    ->toArray();

                $matchedInterests = array_intersect($authInterestIds, $postUserInterests);

                // Interest list
                $interestData = InterestMaster::whereIn('id', $postUserInterests)->get()->map(function ($interest) use ($authInterestIds) {
                    return [
                        'interest_name' => $interest->interest_name,
                        'interest_match' => in_array($interest->id, $authInterestIds),
                    ];
                });
                $user_profile = UserProfileImage::where('user_id', $post->user_id)
                    ->where('is_default', true)->first();
                $is_friend = PostController::isFriend($authUser->id, $post->user_id);
                return [
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'user_name' => $post->user->name ?? '',
                    'activity' => $post->activity,
                    'location' => $post->location,
                    'meet_at' => $post->meet_at,
                    'meet_with' => $post->meet_with,
                    'discussion_topic' => $post->discussion_topic,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                    'interest_match_count' => count($matchedInterests),
                    'user_interest' => $interestData,
                    'user_image' => $user_profile ? concatAppUrl($user_profile->image_name) : null,
                    'is_friend' => $is_friend,

                ];
            });

            // Sort posts
            $sorted = $processed->sortBy([
                [$orderBy, $sortDirection]
            ])->values();

            // Pagination
            $paginated = $sorted->slice($skip, $take)->values();
            $total = $sorted->count();
            $currentPage = floor($skip / $take) + 1;

            return response()->json([
                'code' => 200,
                'message' => 'Post List',
                'data' => $paginated,
                'count' => $paginated->count(),
                'total' => $total,
                'current_page' => $currentPage,
                'per_page' => $take,
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong.',
                'data' => [],
            ]);
        }
    }

    public function getUserAllPosts(Request $request)
    {
        try {
            $authUser = Auth::user();

            $authInterestIds = UserInterest::where('user_id', $authUser->id)
                ->pluck('interest_id')
                ->toArray();

            // Request inputs
            $skip = $request->skip ?? 0;
            $take = $request->take ?? 10;
            $orderBy = $request->order_by ?? 'created_at';
            $sortDirection = $request->sort ?? 'desc';

            // Fetch only authenticated user's posts
            $allPosts = UserPosts::where('user_id', $authUser->id)
                ->with('user')
                ->get();

            // Filter posts that have at least one interest
            $filtered = $allPosts->filter(function ($post) use ($authInterestIds) {
                $postUserInterests = UserInterest::where('user_id', $post->user_id)
                    ->pluck('interest_id')
                    ->toArray();

                $matchedInterests = array_intersect($authInterestIds, $postUserInterests);

                return count($matchedInterests) > 0;
            })->values();

            // Process and format posts
            $processed = $filtered->map(function ($post) use ($authInterestIds, $authUser) {
                $postUserInterests = UserInterest::where('user_id', $post->user_id)
                    ->pluck('interest_id')
                    ->toArray();

                $matchedInterests = array_intersect($authInterestIds, $postUserInterests);

                // Interest list
                $interestData = InterestMaster::whereIn('id', $postUserInterests)->get()->map(function ($interest) use ($authInterestIds) {
                    return [
                        'interest_name' => $interest->interest_name,
                        'interest_match' => in_array($interest->id, $authInterestIds),
                    ];
                });

                $user_profile = UserProfileImage::where('user_id', $post->user_id)
                    ->where('is_default', true)->first();

                return [
                    'id' => $post->id,
                    'user_id' => $post->user_id,
                    'user_name' => $post->user->name ?? '',
                    'activity' => $post->activity,
                    'location' => $post->location,
                    'meet_at' => $post->meet_at,
                    'meet_with' => $post->meet_with,
                    'discussion_topic' => $post->discussion_topic,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                    'interest_match_count' => count($matchedInterests),
                    'user_interest' => $interestData,
                    'user_image' => $user_profile ? concatAppUrl($user_profile->image_name) : null,
                    'is_friend' => false,
                ];
            });

            // Sort posts
            $sorted = $processed->sortBy([[$orderBy, $sortDirection]])->values();

            // Pagination
            $paginated = $sorted->slice($skip, $take)->values();
            $total = $sorted->count();
            $currentPage = floor($skip / $take) + 1;

            return response()->json([
                'code' => 200,
                'message' => 'My Post List',
                'data' => $paginated,
                'count' => $paginated->count(),
                'total' => $total,
                'current_page' => $currentPage,
                'per_page' => $take,
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong.',
                'data' => [],
            ]);
        }
    }
}
