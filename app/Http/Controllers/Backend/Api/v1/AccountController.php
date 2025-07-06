<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBlock;
use App\Models\UserReport;
use App\Models\UserRequest;
use App\Services\AccountService;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AccountController extends Controller
{
    public $accountService;
    public $generalService;

    public function __construct(AccountService $accountService, GeneralService $generalService)
    {
        $this->accountService = $accountService;
        $this->generalService = $generalService;
    }

    /**
     * @OA\Post(
     *     path="/api/delete-my-account",
     *     summary="Delete a user account",
     *     tags={"User Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 required={"user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User account successfully deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Deleted successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 additionalProperties=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exists", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function deleteMyAccount(Request $request)
    {
        $validator = Validator::make(['user_id' => $request->user_id], [
            'user_id' => 'required|integer',
        ]);

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        if (Auth::user()->id == $request->user_id) {
            $this->accountService->deleteMyAccount($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Deleted successfully!',
                'error' => null,
                'data' => null,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'Can not Find this user'],
                'data' => null,
            ], 422);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/block-user",
     *     summary="Block a user",
     *     tags={"User Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="blocked_by_user_id", type="integer", example=1),
     *                 @OA\Property(property="blocked_user_id", type="integer", example=2),
     *                 required={"blocked_by_user_id", "blocked_user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully blocked",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Blocked successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 additionalProperties=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"The blocked_by_user_id field is required.", "The blocked_user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function blockUser(Request $request)
    {
        $validator = Validator::make(['blocked_by_user_id' => $request->blocked_by_user_id, 'blocked_user_id' => $request->blocked_user_id], [
            // 'blocked_by_user_id' => 'required|integer|exists:users,id',
            'blocked_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $data = $this->accountService->blockUser($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Blocked successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/report-user",
     *     summary="Report a user",
     *     tags={"User Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="reported_by_user_id", type="integer", example=1),
     *                 @OA\Property(property="reported_user_id", type="integer", example=2),
     *                 required={"reported_by_user_id", "reported_user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User reported successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reported successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 additionalProperties=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"The reported_by_user_id field is required.", "The reported_user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function reportUser(Request $request)
    {
        $validator = Validator::make(['reported_by_user_id' => $request->reported_by_user_id, 'reported_user_id' => $request->reported_user_id], [
            // 'reported_by_user_id' => 'required|integer|exists:users,id',
            'reported_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $checkReportExist = UserReport::where('reported_by_user_id', auth()->id())->where('reported_user_id', $request->reported_user_id)->exists();

        if ($checkReportExist) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'You have already reported this user!'],
                'data' => null,
            ], 422);
        }

        $data = $this->accountService->reportUser($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Reported successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/favourite-user",
     *     summary="Add a user to favorites",
     *     tags={"User Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="favorited_by_user_id", type="integer", example=1),
     *                 @OA\Property(property="favorited_user_id", type="integer", example=2),
     *                 required={"favorited_by_user_id", "favorited_user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User successfully added to favorites",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reported successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 additionalProperties=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"The favorited_by_user_id field is required.", "The favorited_user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function favouriteUser(Request $request)
    {
        $validator = Validator::make(['favorited_by_user_id' => $request->favorited_by_user_id, 'favorited_user_id' => $request->favorited_user_id], [
            // 'favorited_by_user_id' => 'required|integer|exists:users,id',
            'favorited_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $this->accountService->favouriteUser($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Added to favourite successfully!',
            'error' => null,
            'data' => null,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/fetch-user-detail",
     *     summary="Fetch user details by user ID",
     *     security={{"bearerAuth": {}}},
     *     tags={"User Management"},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="The ID of the user to fetch details for",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User details fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="contact", type="string", example="1234567890"),
     *                 @OA\Property(property="apple_id", type="string", example="apple123"),
     *                 @OA\Property(property="google_id", type="string", example="google123"),
     *                 @OA\Property(property="fcm_token", type="string", example="fcmToken123"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", example="2024-08-20T12:00:00Z"),
     *                 @OA\Property(property="invitation_code_id", type="integer", example=10),
     *                 @OA\Property(property="user_status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-20T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-20T12:00:00Z"),
     *                 @OA\Property(property="dob", type="string", example="1990-01-01"),
     *                 @OA\Property(property="gender", type="string", example="male"),
     *                 @OA\Property(property="bio", type="string", example="Lorem ipsum dolor sit amet."),
     *                 @OA\Property(property="location", type="string", example="New York"),
     *                 @OA\Property(property="location_latitude", type="number", format="float", example=40.7128),
     *                 @OA\Property(property="location_longitude", type="number", format="float", example=-74.0060),
     *                 @OA\Property(property="is_smoke", type="boolean", example=false),
     *                 @OA\Property(property="is_drink", type="boolean", example=true),
     *                 @OA\Property(property="is_distance_preference", type="boolean", example=true),
     *                 @OA\Property(property="distance_preference", type="number", format="float", example=50.0),
     *                 @OA\Property(property="is_age_preference", type="boolean", example=true),
     *                 @OA\Property(property="from_age_preference", type="integer", example=18),
     *                 @OA\Property(property="to_age_preference", type="integer", example=30),
     *                 @OA\Property(property="is_mutual_interest_preference", type="boolean", example=true),
     *                 @OA\Property(property="min_mutual_interest", type="integer", example=5),
     *                 @OA\Property(property="gender_preference", type="string", example="female"),
     *                 @OA\Property(property="is_display_in_search", type="boolean", example=true),
     *                 @OA\Property(property="is_display_in_recommendation", type="boolean", example=true),
     *                 @OA\Property(property="is_display_location", type="boolean", example=true),
     *                 @OA\Property(property="is_display_age", type="boolean", example=true),
     *                 @OA\Property(property="is_notification_on", type="boolean", example=true),
     *                 @OA\Property(property="is_agree_term_condition", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exists!", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function userDetails(Request $request)
    {
        $validator = Validator::make(['user_id' => $request->user_id], [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $user = User::find($request->user_id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User not found.'],
                'data' => null,
            ], 404);
        }

        if (! $user->hasRole('App User')) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User not found.'],
                'data' => null,
            ], 404);
        }

        $column = [
            'users.id',
            'users.name',
            'users.username',
            'users.email',
            'users.contact',
            'users.apple_id',
            'users.google_id',
            'users.fcm_token',
            'users.email_verified_at',
            'users.invitation_code_id',
            'users.user_status',
            'users.created_at',
            'users.updated_at',
            'user_details.dob',
            'user_details.gender',
            'user_details.bio',
            'user_details.location',
            'user_details.location_latitude',
            'user_details.location_longitude',
            'user_details.is_smoke',
            'user_details.is_drink',
            'user_details.is_distance_preference',
            'user_details.distance_preference',
            'user_details.is_age_preference',
            'user_details.from_age_preference',
            'user_details.to_age_preference',
            'user_details.is_mutual_interest_preference',
            'user_details.gender_preference',
            'user_details.is_display_in_search',
            'user_details.is_display_in_recommendation',
            'user_details.is_display_location',
            'user_details.is_display_age',
            'user_details.is_notification_on',
            'user_details.is_agree_term_condition',
        ];

        $data = $this->accountService->userDetails($request->user_id, $column);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/fetch-questions",
     *     summary="Fetch a list of questions",
     *     tags={"Question Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Questions fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="question", type="string", example="What is your favorite color?")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching questions."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function fetchQuestions()
    {
        $data = $this->accountService->fetchQuestions();
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/fetch-user-que-ans",
     *     summary="Retrieve user questions and answers",
     *     tags={"User Questions"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID of the user whose questions and answers are to be fetched",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User questions and answers fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="question_id", type="integer", example=1),
     *                     @OA\Property(property="question", type="string", example="What is your favorite color?"),
     *                     @OA\Property(property="answer", type="string", example="Blue")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exists", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function fetchUserQueAns(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $data = $this->accountService->fetchUserQueAns($request->user_id);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user-feedback",
     *     summary="Submit feedback for a user",
     *     tags={"Feedback Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=4),
     *                 @OA\Property(property="feedback_type_id", type="integer", example=2),
     *                 @OA\Property(property="comment", type="string", example="Great service!"),
     *                 required={"user_id", "rating", "feedback_type_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Feedback stored successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Feedback stored successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="rating", type="integer", example=4),
     *                 @OA\Property(property="feedback_type_id", type="integer", example=2),
     *                 @OA\Property(property="comment", type="string", example="Great service!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-20T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-20T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exist", "The rating field is required.", "The feedback_type_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function userFeedback(Request $request)
    {
        $validator = Validator::make($request->only(['user_id', 'rating', 'feedback_type_id', 'comment']), [
            'user_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'feedback_type_id' => 'nullable|integer|exists:feedback_type_masters,id',
            'comment' => 'nullable|string|min:100|max:1500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        // $existsUser = User::where('id', $request->user_id)->exists();

        // if (! $existsUser) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => null,
        //         'error' => ['no_user' => 'User Does not exists'],
        //         'data' => null,
        //     ], 422);
        // }

        $data = $this->accountService->userFeedback($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Feedback store successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-user-settings",
     *     summary="Fetch user settings by user ID",
     *     tags={"User Settings"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User settings fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="is_notification_on", type="boolean", example=true),
     *                 @OA\Property(property="is_display_location", type="boolean", example=true),
     *                 @OA\Property(property="is_display_age", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exist", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function getUserSettings(Request $request)
    {
        $validator = Validator::make($request->only(['user_id']), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $column = [
            'user_details.is_notification_on',
            'user_details.is_display_location',
            'user_details.is_display_age',
        ];

        $data = $this->accountService->getUserSettings($request->user_id, $column);
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/edit-user-settings",
     *     summary="Update user settings",
     *     tags={"User Settings"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="is_notification_on", type="boolean", example=true),
     *                 @OA\Property(property="is_display_location", type="boolean", example=true),
     *                 @OA\Property(property="is_display_age", type="boolean", example=true),
     *                 required={"user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User settings updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User settings updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="is_notification_on", type="boolean", example=true),
     *                 @OA\Property(property="is_display_location", type="boolean", example=true),
     *                 @OA\Property(property="is_display_age", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User Does not exists"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error when updating user settings",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update user settings."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function updateUserSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'is_notification_on' => 'nullable|boolean',
            'is_display_location' => 'nullable|boolean',
            'is_display_age' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $data = $request->only([
            'user_id',
            'is_notification_on',
            'is_display_location',
            'is_display_age',
        ]);

        $updateSuccess = $this->accountService->updateUserSettings($data);

        if (! $updateSuccess) {
            return response()->json([
                'success' => false,
                'message' => ['failed' => 'Failed to update user settings.'],
                'data' => null,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-interest",
     *     summary="Fetch user interests by user ID",
     *     tags={"User Interests"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User interests fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="interest_id", type="integer", example=2),
     *                     @OA\Property(property="interest_name", type="string", example="Music"),
     *                     @OA\Property(property="interest_color", type="string", example="#FF5733")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User Does not exists"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function usersInterest(Request $request)
    {
        $column = [
            // 'user_interests.user_id',
            'interest_masters.id as id',
            'interest_masters.interest_name',
            'interest_masters.interest_color',
        ];

        $data = $this->accountService->usersInterest($column);
        $notificationCount = $this->accountService->userNotificationCount();

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'notification_count' => $notificationCount,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-cennections",
     *     summary="Fetch user connections by user ID",
     *     tags={"User Connections"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User connections fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="from_user_id", type="integer", example=1),
     *                     @OA\Property(property="to_user_id", type="integer", example=2),
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="username", type="string", example="johndoe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or user not found error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User does not exist"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function userConnections(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User does not exist'],
                'data' => null,
            ], 422);
        }

        $columns = [
            'user_requests.from_user_id',
            'user_requests.to_user_id',
            'users.id',
            'users.username',
            'users.email',
        ];

        $data = $this->accountService->userConnections($request->user_id, $columns);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/edit-user-profile",
     *     summary="Update user profile information",
     *     tags={"User Profile"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="location", type="string", example="New York"),
     *                 @OA\Property(property="bio", type="string", example="This is my bio."),
     *                 @OA\Property(property="dob", type="string", example="01-01-1990", format="date", description="Date of birth in MM-DD-YYYY format"),
     *                 @OA\Property(property="gender", type="integer", example=1, description="1 for male, 2 for female, 3 for other"),
     *                 @OA\Property(property="is_smoke", type="integer", example=1, description="0 for no, 1 for yes, 2 for sometimes"),
     *                 @OA\Property(property="is_drink", type="integer", example=2, description="0 for no, 1 for socially, 2 for frequently, 3 for heavily"),
     *                 required={"user_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User settings updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="location", type="string", example="New York"),
     *                 @OA\Property(property="bio", type="string", example="This is my bio."),
     *                 @OA\Property(property="dob", type="string", example="01-01-1990"),
     *                 @OA\Property(property="gender", type="integer", example=1),
     *                 @OA\Property(property="is_smoke", type="integer", example=1),
     *                 @OA\Property(property="is_drink", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User Does not exists"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update user profile",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update user settings."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function updateUserProfle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            // 'username' => 'required|string|max:255|unique:users,username,' . $request->user_id,
            'name' => 'required|string|max:100',
            'location' => 'nullable|string|max:191',
            'bio' => 'nullable|string',
            'dob' => 'nullable|date_format:m-d-Y',
            'gender' => 'nullable|integer|in:1,2,3',
            'is_smoke' => 'nullable|integer|in:0,1,2',
            'is_drink' => 'nullable|integer|in:0,1,2,3',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Display Name cannot be blank.',
            // 'username.unique' => 'This username is already taken. Please choose another one.',
            'name.max' => 'Username must be max 100 character long',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        if (auth()->id() != $request->user_id) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'User not found!'],
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $data = $request->only([
            'user_id',
            'name',
            'username',
            'location',
            'bio',
            'dob',
            'gender',
            'is_smoke',
            'is_drink',
            'latitude',
            'longitude'
        ]);

        $updateSuccess = $this->accountService->updateUserProfile($data);

        if (! $updateSuccess) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Failed to update user settings.'],
                'data' => null,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User settings updated successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/get-user-profile",
     *     summary="Retrieve user profile data",
     *     tags={"User Profile"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="ID of the user whose profile data is to be fetched",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User profile data fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="location", type="string", example="New York"),
     *                 @OA\Property(property="bio", type="string", example="This is my bio."),
     *                 @OA\Property(property="dob", type="string", example="01-01-1990"),
     *                 @OA\Property(property="gender", type="integer", example=1),
     *                 @OA\Property(property="is_smoke", type="integer", example=1),
     *                 @OA\Property(property="is_drink", type="integer", example=2),
     *                 @OA\Property(
     *                     property="user_question_answers",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="question_id", type="integer", example=10),
     *                         @OA\Property(property="answer", type="string", example="Answer to the question")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exists", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function getUserProfleData(Request $request)
    {
        $validator = Validator::make($request->only(['user_id']), [
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        if ($request->user_id != auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $column = [
            'users.id',
            'users.name',
            'user_details.location',
            'user_details.bio',
            'user_details.dob',
            'user_details.gender',
            'user_details.is_smoke',
            'user_details.is_drink',
            'user_question_answers.question_id',
            'user_question_answers.answer',
        ];

        $data = $this->accountService->getUserProfleData($request->user_id);
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/edit-user-que-ans",
     *     summary="Update user's answer to a specific question",
     *     tags={"User Questions"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="question_id", type="integer", example=1),
     *                 @OA\Property(property="answer", type="string", example="Updated answer"),
     *                 required={"user_id", "question_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User's answer updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="question_id", type="integer", example=1),
     *                 @OA\Property(property="answer", type="string", example="Updated answer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="array", @OA\Items(type="string"), example={"User Does not exists", "The user_id field is required."}),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to update the answer",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function updateUserQueAns(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'que_ans' => ['nullable', 'array', 'max:5'],
            'que_ans.*.question_id' => 'nullable|integer|exists:question_masters,id',
            'que_ans.*.answer' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $formattedErrors = [];

            foreach ($errors as $field => $messages) {
                if (strpos($field, 'que_ans') === 0) {
                    $fieldParts = explode('.', $field);
                    $index = $fieldParts[1] ?? null;

                    if ($index) {
                        $invalidData = $request->input('que_ans')[$index] ?? null;

                        if (isset($messages)) {
                            foreach ($messages as $message) {
                                $formattedErrors[$index] = 'Invalid question_id ' . ($invalidData['question_id'] ?? 'unknown');
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => null,
                'error' => $formattedErrors,
                'data' => null,
            ], 422);
        }

        $queAns = $request->input('que_ans');
        $updateSuccess = $this->accountService->updateUserQueAns($queAns);

        if (!$updateSuccess) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully!',
                'error' => null,
                'data' => null,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'error' => null,
            'data' => $updateSuccess,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/user-profile-images",
     *     summary="Upload profile images for a user",
     *     tags={"User Profile"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=1,
     *                     description="The ID of the user for whom the images are being uploaded."
     *                 ),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         format="binary",
     *                         description="An image file to be uploaded."
     *                     ),
     *                     description="Array of images to upload. Maximum file size is 10MB per image."
     *                 ),
     *                 required={"user_id", "images"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Images uploaded successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true,
     *                 description="Indicates if the images were uploaded successfully."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Images uploaded successfully.",
     *                 description="Success message."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false,
     *                 description="Indicates if the request failed due to validation errors."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 example={
     *                     "user_id": "The user id field is required",
     *                     "images": "The images field is required"
     *                 },
     *                 description="Validation error messages."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 description="Additional error details."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false,
     *                 description="Indicates if the request failed due to an internal server error."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error occurred while uploading images.",
     *                 description="Error message."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 description="Additional error details."
     *             )
     *         )
     *     )
     * )
     */
    public function storeUserProfileImages(Request $request)
    {
        $rules = [
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'deleted_images.*' => 'nullable|integer|exists:user_profile_images,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        try {

            $array =  [
                'image_1',
                'image_2',
                'image_3'
            ];

            foreach ($array as $value) {
                $images = $request->file($value);
                $this->accountService->storeImages($images, []);
            }

            $deleteImage = $request->deleted_images;
            $this->accountService->storeImages(null, $deleteImage);
            return response()->json([
                'success' => true,
                'error' => null,
                'message' => 'Images Uploaded successfully.',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/get-user-profile-images",
     *     summary="Retrieve profile images for a user",
     *     tags={"User Profile"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1,
     *             description="The ID of the user whose profile images are to be fetched."
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile images retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true,
     *                 description="Indicates if the request was successful."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Fetched Successfully.",
     *                 description="Success message."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string",
     *                     format="uri",
     *                     description="The URL of the profile image."
     *                 ),
     *                 description="Array of profile image URLs."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No images found for this user",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false,
     *                 description="Indicates if the request was unsuccessful."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="No images found for this user.",
     *                 description="Error message."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false,
     *                 description="Indicates if the request failed due to validation errors."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 example={
     *                     "user_id": "The user id field is required"
     *                 },
     *                 description="Validation error messages."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 description="Additional error details."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false,
     *                 description="Indicates if the request failed due to an internal server error."
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An error occurred while retrieving images.",
     *                 description="Error message."
     *             )
     *         )
     *     )
     * )
     */
    public function getUserProfileImages()
    {
        try {
            $images = $this->accountService->getUserImages(auth()->id());
            $defaultImages = $this->generalService->getDefaultProfilePicture();

            if ($images->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'No images found for this user.'],
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fetched Successfully.',
                'error' => null,
                'default_profile_picture' => $defaultImages,
                'data' => $images,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/fetch-near-users",
     *     summary="Fetch a list of nearby users",
     *     tags={"User Management"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Nearby users fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="username", type="string", example="johndoe"),
     *                     @OA\Property(property="location", type="string", example="New York"),
     *                     @OA\Property(property="distance", type="number", format="float", example=5.5)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while fetching nearby users."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
    public function fetchNearUsers()
    {
        $data = $this->accountService->fetchNearUsers();
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/edit-user-interest",
     *     summary="Update User Interest",
     *     description="Update interests for a specific user. Only provided interests will be updated.",
     *     tags={"User Interests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id"},
     *             @OA\Property(property="user_id", type="integer", example=1, description="ID of the user whose interests are to be updated."),
     *             @OA\Property(
     *                 property="interest_id",
     *                 type="array",
     *                 @OA\Items(type="integer", example=2, description="List of interest IDs to be updated.")
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User interest updated successfully.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User interest updated successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="interest_id", type="array", @OA\Items(type="integer", example=2))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user does not exist.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User Does not exists"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error while updating user interest.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to update user interest."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     * )
     */
    public function updateUserInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'interest_id' => 'required|array|exists:interest_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $existsUser = User::where('id', $request->user_id)->exists();

        if (! $existsUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User Does not exists'],
                'data' => null,
            ], 422);
        }

        $data = $request->only([
            'user_id',
            'interest_id',
        ]);

        $updateSuccess = $this->accountService->updateUserInterest($data);

        if (! $updateSuccess) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Failed to update user interest.'],
                'data' => null,
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User interest updated successfully!',
            'error' => null,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/add-to-my-interest",
     *     summary="Add an interest to the user's profile",
     *     tags={"User Interests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID of the user",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="interest_id",
     *                     type="integer",
     *                     description="ID of the interest",
     *                     example=10
     *                 ),
     *                 required={"user_id", "interest_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully added the interest to the user's profile",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Added successfully!"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="interest_id",
     *                     type="integer",
     *                     example=10
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user-related issue",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(
     *                     property="no_user",
     *                     type="string",
     *                     example="User Does not exists"
     *                 ),
     *                 @OA\Property(
     *                     property="failed",
     *                     type="string",
     *                     example="Failed to update."
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 nullable=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(
     *                     property="failed",
     *                     type="string",
     *                     example="Failed to update."
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 nullable=true
     *             )
     *         )
     *     )
     * )
     */
    public function addToMyInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'interest_id' => 'required|integer|exists:interest_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $user = Auth::user();

        if (isset($user) && $user->id == $request->user_id && $user->userRole->first()->name == 'App User') {
            $existsUser = User::where('id', $request->user_id)->exists();

            if (! $existsUser) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['no_user' => 'User Does not exists'],
                    'data' => null,
                ], 422);
            }

            $data = $request->only([
                'user_id',
                'interest_id',
            ]);

            $updateSuccess = $this->accountService->addToMyInterest($data);

            if (! $updateSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'Failed to update.'],
                    'data' => null,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Added successfully!',
                'error' => null,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['no_user' => 'User Not Found'],
            'data' => null,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/remove-from-my-interest",
     *     summary="Remove an interest from the user's profile",
     *     tags={"User Interests"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     description="ID of the user",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="interest_id",
     *                     type="integer",
     *                     description="ID of the interest",
     *                     example=10
     *                 ),
     *                 required={"user_id", "interest_id"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully removed the interest from the user's profile",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Removed successfully!"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="interest_id",
     *                     type="integer",
     *                     example=10
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or user-related issue",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(
     *                     property="no_user",
     *                     type="string",
     *                     example="User Does not exists"
     *                 ),
     *                 @OA\Property(
     *                     property="failed",
     *                     type="string",
     *                     example="Failed to update."
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 nullable=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 nullable=true
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(
     *                     property="failed",
     *                     type="string",
     *                     example="Failed to update."
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 nullable=true
     *             )
     *         )
     *     )
     * )
     */
    public function removeFromMyInterest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'interest_id' => 'required|integer|exists:interest_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $user = Auth::user();

        if (isset($user) && $user->id == $request->user_id && $user->userRole->first()->name == 'App User') {
            $existsUser = User::where('id', $request->user_id)->exists();

            if (! $existsUser) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['no_user' => 'User Does not exists'],
                    'data' => null,
                ], 422);
            }

            $data = $request->only([
                'user_id',
                'interest_id',
            ]);

            $updateSuccess = $this->accountService->removeFromMyInterest($data);

            if (! $updateSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'Failed to update.'],
                    'data' => null,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Removed successfully!',
                'error' => null,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['no_user' => 'User Not Found'],
            'data' => null,
        ]);
    }

    public function sendCennectionReq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'from_user_id' => 'required|integer|exists:users,id',
            'to_user_id' => 'required|integer|exists:users,id',
            'request_comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $user = Auth::user();

        if (!$this->hasRole($request->to_user_id, 'App User') || $request->from_user_id == $request->to_user_id) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Failed to send request, please try again.'],
                'data' => null,
            ], 422);
        }

        $checkUserBlockMe = UserBlock::where('blocked_user_id', auth()->id())
            ->where('blocked_by_user_id', $request->to_user_id)
            ->where('blocked_status', 'blocked')->first();

        $checkBlockUser = UserBlock::where('blocked_user_id', $request->to_user_id)
            ->where('blocked_by_user_id', auth()->id())
            ->where('blocked_status', 'blocked')->first();

        if ($checkUserBlockMe) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'You cannot send a request to this user because they have blocked you.'],
                'data' => null,
            ], 403);
        }

        if ($checkBlockUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'You cannot send a request to this user because you blocked this user.'],
                'data' => null,
            ], 403);
        }

        if (isset($user) && $user->userRole->first()->name == 'App User') {
            $checkReqExists = UserRequest::where('from_user_id', auth()->id())->where('to_user_id', $request->to_user_id)->where('request_status', 'pending')->exists();
            if ($checkReqExists) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['already_sent' => 'You have already sent request to this user'],
                    'data' => null,
                ], 422);
            }

            $checkOponentExists = UserRequest::where('from_user_id', $request->to_user_id)->where('to_user_id', auth()->id())->where('request_status', 'pending')->exists();

            if ($checkOponentExists) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['already_sent' => 'This user has already sent you a request'],
                    'data' => null,
                ], 422);
            }

            $data = $request->only([
                'to_user_id',
                'request_comment',
            ]);

            $updateSuccess = $this->accountService->sendCennectionReq($data);

            if (!$updateSuccess) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'Failed to send request, please try again.'],
                    'data' => null,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Request Send successfully!',
                'error' => null,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['failed' => 'Failed to send request, please try again.'],
            'data' => null,
        ], 422);
    }

    private function hasRole($userId, $roleName)
    {
        $user = User::find($userId);
        if ($user) {
            return $user->roles()->where('name', $roleName)->exists();
        }
        return false;
    }

    public function getPendingReqs()
    {
        $user = Auth::user();
        if (isset($user) && $user->userRole->first()->name == 'App User') {
            $data = $this->accountService->getPendingReqs();
            if ($data === false) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'Failed to fetch data, please try again.'],
                    'data' => null,
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Fetched successfully!',
                'error' => null,
                'request_count' => count($data),
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['failed' => 'Failed to fetch data, please try again.'],
            'data' => null,
        ], 500);
    }

    public function acceptRejectReq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_by' => 'required|integer|exists:users,id',
            'action_to' => 'required|integer|exists:users,id',
            'request_status' => 'required|string|in:accepted,rejected',
            'notification_id' => 'nullable|integer|exists:notifications,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $user = Auth::user();

        if (!$this->hasRole(auth()->id(), 'App User') || $request->action_by == $request->action_to) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'Failed to connect, please try again.'],
                'data' => null,
            ], 422);
        }

        if (isset($user) && $user->userRole->first()->name == 'App User') {
            $existsUser = User::where('id', $request->action_by)->exists();
            if (! $existsUser) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['no_user' => 'User Does not exists'],
                    'data' => null,
                ], 422);
            }

            $checkReqExists = UserRequest::where('to_user_id', $request->action_by)->where('from_user_id', $request->action_to)->first();

            $data = $request->only([
                'action_by',
                'action_to',
                'request_status',
                'notification_id',
            ]);

            if ($checkReqExists) {
                if ($checkReqExists->request_status == 'accepted') {
                    return response()->json([
                        'success' => false,
                        'message' => null,
                        'error' => ['already_connected' => 'You are already connected with this user'],
                        'data' => null,
                    ], 422);
                }
                if ($request->request_status == 'accepted' || $request->request_status == 'rejected') {
                    $this->accountService->acceptRejectReq($checkReqExists, $data);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['failed' => 'You are not connected with this user, please send request first.'],
                    'data' => null,
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'successfully connected!',
                'error' => null,
                'data' => $data,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['failed' => 'Failed to connect, please try again.'],
            'data' => null,
        ], 500);
    }

    public function getMyConnections(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $perPage = $request->input('per_page', 100000);
        $page = $request->input('page', 1);

        $connections = $this->accountService->getMyConnections(auth()->id(), $perPage, $page);

        if (!$connections) {
            return response()->json([
                'success' => true,
                'message' => "No connections found.",
                'error' => null,
                'data' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => 0,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Fetched successfully!",
            'error' => null,
            'data' => $connections->items(),
            'pagination' => [
                'total' => $connections->total(),
                'per_page' => $connections->perPage(),
                'current_page' => $connections->currentPage(),
                'last_page' => $connections->lastPage(),
                'next_page_url' => $connections->nextPageUrl(),
                'prev_page_url' => $connections->previousPageUrl(),
            ],
        ]);
    }

    public function getMyFavoriteUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer|min:1',
            'page' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $perPage = $request->input('per_page', 100000);
        $page = $request->input('page', 1);

        $favorites = $this->accountService->getMyFavoriteUsers(auth()->id(), $perPage, $page);

        if (!$favorites) {
            return response()->json([
                'success' => true,
                'message' => "No favorite users found.",
                'error' => null,
                'data' => [],
                'pagination' => [
                    'total' => 0,
                    'per_page' => $perPage,
                    'current_page' => $page,
                    'last_page' => 0,
                    'next_page_url' => null,
                    'prev_page_url' => null,
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Fetched successfully!",
            'error' => null,
            'data' => $favorites->items(),
            'pagination' => [
                'total' => $favorites->total(),
                'per_page' => $favorites->perPage(),
                'current_page' => $favorites->currentPage(),
                'last_page' => $favorites->lastPage(),
                'next_page_url' => $favorites->nextPageUrl(),
                'prev_page_url' => $favorites->previousPageUrl(),
            ],
        ]);
    }

    public function deleteConnection(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id',
        ]);

        $friendId = $request->input('friend_id');
        $userId = Auth::id();

        try {
            $data = $this->accountService->deleteConnection($userId, $friendId);
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => 'User not found in your connection',
                    'data' => null,
                ], 422);
            }
            return response()->json([
                'success' => true,
                'message' => "Connection deleted successfully!",
                'error' => null,
                'data' => null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'Failed to delete connection.',
                'data' => null,
            ], 422);
        }
    }

    public function fetchFeedbackQue()
    {
        $data = $this->accountService->fetchFeedbackQue();
        $givenRating = $this->accountService->givenRatingForAuthUser();
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'givenRating' => $givenRating ?? 0,
            'data' => $data,
        ]);
    }

    public function removeFavouriteUser(Request $request)
    {
        $validator = Validator::make([
            'favorited_user_id' => $request->favorited_user_id
        ], [
            'favorited_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $this->accountService->removeFavouriteUser($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Removed from favourites successfully!',
            'error' => null,
            'data' => null,
        ]);
    }

    public function storeUserPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|exists:policies,slug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $slug = $request->input('slug');


        $policyMap = [
            'about-us' => ['is_accept_about_us' => true],
            'community-guidlines' => ['is_accept_guidelines' => true],
        ];

        $data = $policyMap[$slug] ?? null;

        if ($data) {
            $this->accountService->storeUserPolicy($data);
            return response()->json([
                'success' => true,
                'message' => 'Stored successfully!',
                'error' => null,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid policy slug.',
            'error' => null,
            'data' => null,
        ], 400);
    }

    public function userPreferences(Request $request)
    {
        $data = $this->accountService->userPreferences();
        if (is_array($data) && !empty($data)) {
            $data = $data[0];
        }
        return response()->json([
            'success' => true,
            'message' => 'Fetched Successfully.',
            'error' => null,
            'data' => $data,
        ], 200);
    }

    public function storeUserPreferences(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'nullable|string',
            'location_latitude' => 'nullable|numeric',
            'location_longitude' => 'nullable|numeric',
            'is_distance_preference' => 'nullable|boolean',
            'distance_preference' => 'nullable|numeric',
            'dob' => 'nullable|date',
            'is_age_preference' => 'nullable|boolean',
            'from_age_preference' => 'nullable|integer',
            'to_age_preference' => 'nullable|integer',
            'is_mutual_interest_preference' => 'nullable|boolean',
            'min_mutual_interest' => 'nullable|numeric',
            'gender_preference' => 'required|in:1,2,3',
            'is_display_in_search' => 'nullable|boolean',
            'is_display_in_recommendation' => 'nullable|boolean',
        ], [
            'gender_preference.required' => 'Please select your gender preference.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $data = $request->only([
            'location',
            'location_latitude',
            'location_longitude',
            'is_distance_preference',
            'distance_preference',
            'dob',
            'is_age_preference',
            'from_age_preference',
            'to_age_preference',
            'is_mutual_interest_preference',
            'min_mutual_interest',
            'gender_preference',
            'is_display_in_search',
            'is_display_in_recommendation',
        ]);

        $result = $this->accountService->storeUserPreferences($data);
        $keysToInclude = [
            'location',
            'location_latitude',
            'location_longitude',
            'is_distance_preference',
            'distance_preference',
            'dob',
            'is_age_preference',
            'from_age_preference',
            'to_age_preference',
            'is_mutual_interest_preference',
            'min_mutual_interest',
            'gender_preference',
            'is_display_in_search',
            'is_display_in_recommendation',
        ];

        $filteredResult = $result->only($keysToInclude);
        foreach ($filteredResult as $key => $value) {
            if (str_starts_with($key, 'is_')) {
                $filteredResult[$key] = (bool) $value;
            }
        }
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Preferences Updated successfully.',
                'error' => null,
                'data' => $filteredResult,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store preferences.',
                'error' => null,
                'data' => null,
            ], 500);
        }
    }

    public function defaultUserImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|integer|exists:user_profile_images,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $data = $request->only('image_id');

        $result = $this->accountService->defaultUserImage($data);

        if ($result === true) {
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully.',
                'error' => null,
                'data' => null,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Image not found for this user.',
                'error' => null,
                'data' => null,
            ], 422);
        }
    }

    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'removed_user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $result = $this->accountService->removeUser($request->removed_user_id);

        if ($result === true) {
            return response()->json([
                'success' => true,
                'message' => 'User Removed successfully.',
                'error' => null,
                'data' => $result,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to remove user!',
                'error' => null,
                'data' => null,
            ], 422);
        }
    }
}
