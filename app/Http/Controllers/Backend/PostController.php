<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\InterestMaster;
use App\Models\User;
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

    public function getMatchedPosts(Request $request)
    {
        try {
            $authUser = Auth::user();
            $userInterestIds = UserInterest::where('user_id', $authUser->id)->pluck('interest_id')->toArray();

            $otherPosts = UserPosts::where('user_id', '!=', $authUser->id)
                ->with('user')
                ->get();

            $result = $otherPosts->map(function ($post) use ($userInterestIds, $authUser, $request) {
                $postUser = $post->user;
                $user_profile = UserProfileImage::where('user_id', $postUser->id)->first();
                $postUserInterestIds = UserInterest::where('user_id', $post->user_id)->pluck('interest_id')->toArray();

                // 1. Field match scoring (5 fields = 100%)
                $matchFields = 0;
                if ($post->activity === $request->activity) $matchFields++;
                if ($post->location === $request->location) $matchFields++;
                if ($post->meet_at === $request->meet_at) $matchFields++;
                if ($post->meet_with === $request->meet_with) $matchFields++;
                if ($post->discussion_topic === $request->discussion_topic) $matchFields++;
                $matchPercentage = $matchFields * 20;

                // 2. Prepare interest list
                $interestData = InterestMaster::whereIn('id', $postUserInterestIds)->get()->map(function ($interest) use ($userInterestIds) {
                    return [
                        'interest_name' => $interest->interest_name,
                        'interest_match' => in_array($interest->id, $userInterestIds),
                    ];
                });

                // 3. Mutual connections
                $mutualConnectionCount = PostController::getMutualConnectionCount($authUser->id, $postUser->id);

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
                    'match_percentage' => $matchPercentage,
                    'user_image' => $user_profile ? $user_profile->image_name : null,
                    'user_interest' => $interestData,
                ];
            });

            // Split into similar vs interest match
            $similarPosts = $result->filter(fn($post) => $post['match_percentage'] >= 40)->values();
            $interestMatches = $result->filter(fn($post) => $post['match_percentage'] < 40 && $post['match_percentage'] >= 20)->values();
            $interestMatchIds = $interestMatches->pluck('id')->toArray();
            $similarPostIds = $similarPosts->pluck('id')->toArray();
            $existingPostIds = array_unique(array_merge($interestMatchIds, $similarPostIds));

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

                    $postData = [
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
                        'match_percentage' => 0, // no field match used
                        'user_image' => $user_profile ? $user_profile->image_name : null,
                        'user_interest' => $interestData,
                        'common_interest_count' => count($commonInterests),
                    ];

                    $interestPostsArray[] = $postData;
                }
            }

            // Sort by common_interest_count descending
            usort($interestPostsArray, function ($a, $b) {
                return $b['common_interest_count'] <=> $a['common_interest_count'];
            });

            // Add sorted posts to interestMatches
            foreach ($interestPostsArray as $postData) {
                unset($postData['common_interest_count']); // remove helper field
                $interestMatches->push($postData);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Post List',
                'data' => [
                    'similar_posts' => $similarPosts,
                    'interest_matches' => $interestMatches,
                ],
                'count' => [
                    'similar_posts' => $similarPosts->count(),
                    'interest_matches' => $interestMatches->count(),
                ],
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
}
