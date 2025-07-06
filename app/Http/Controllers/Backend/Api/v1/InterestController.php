<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserInterest;
use App\Services\GeneralService;
use App\Services\InterestService;
use Illuminate\Http\Request;
use Validator;

class InterestController extends Controller
{
    public $interestService;
    public $generalService;

    public function __construct(InterestService $interestService, GeneralService $generalService)
    {
        $this->interestService = $interestService;
        $this->generalService = $generalService;
    }

    /**
     * @OA\Get(
     *     path="/api/interest-list",
     *     summary="Fetch the list of interests",
     *     tags={"Interests"},
     *     security={{ "sanctumAuth": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Fetched successfully!",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Fetched successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object", properties={
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Sports")
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */

    public function fetchInterestList(Request $request)
    {
        $interests = $this->interestService->fetchInterestList($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $interests,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/interest-based-user",
     *     summary="Fetch Users Based on Interest",
     *     description="Retrieves users related to a specific interest based on the provided `interest_id`. The `interest_id` must be valid and exist in the database.",
     *     operationId="fetchInterestRelatedUser",
     *     tags={"Interests"},
     *     @OA\Parameter(
     *         name="interest_id",
     *         in="query",
     *         description="The ID of the interest to fetch related users for.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Users fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Fetched successfully!"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="user_id",
     *                         type="integer",
     *                         example=123
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         example="john.doe@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="interest_id",
     *                         type="integer",
     *                         example=1
     *                     )
     *                 )
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
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string"
     *                 ),
     *                 example={"The given interest_id is invalid."}
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Interest not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Interest not found."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    // public function fetchInterestRelatedUser(Request $request)
    // {
    //     $validator = Validator::make(['interest_id' => $request->interest_id], [
    //         'interest_id' => 'required|integer|exists:interest_masters,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => null,
    //             'error' => formatValidationErrors($validator->errors()),
    //             'data' => null,
    //         ], 422);
    //     }

    //     $columns = [
    //         'users.name',
    //         'users.username',
    //         'users.email',
    //         'user_details.user_id',
    //         'user_details.dob',
    //         'user_details.gender',
    //         'user_details.bio',
    //         // 'user_profile_images.image_name',
    //     ];

    //     $data = $this->interestService->fetchInterestRelatedUser($request->interest_id, $columns);
    //     $existsInterest = $this->interestService->isInterestExistsWithUser($request->interest_id);
    //     $relatedInterest = $this->interestService->relatedInterest($request->interest_id);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Fetched successfully!',
    //         'error' => null,
    //         'interest_exists' => $existsInterest,
    //         'data' => $data,
    //         'related_interest' => $relatedInterest,
    //     ]);
    // }

    public function fetchInterestRelatedUser(Request $request)
    {
        $validator = Validator::make(['interest_id' => $request->interest_id], [
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

        $perPage = $request->input('per_page', 10); // Default to 10 if not provided
        $page = $request->input('page', 1); // Default to 1 if not provided

        $columns = [
            'users.name',
            'users.username',
            'users.email',
            'user_details.user_id',
            'user_details.dob',
            'user_details.gender',
            'user_details.bio',
            // 'user_profile_images.image_name',
        ];

        $data = $this->interestService->fetchInterestRelatedUser($request->interest_id, $columns, $perPage, $page);
        $existsInterest = $this->interestService->isInterestExistsWithUser($request->interest_id);
        $relatedInterest = $this->interestService->relatedInterest($request->interest_id);
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'interest_exists' => $existsInterest,
            'data' => $data->items(),
            'related_interest' => $relatedInterest,
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ],
        ]);
    }

    // public function fetchRecomUser()
    // {
    //     $columns = [
    //         'users.name',
    //         'users.username',
    //         'users.email',
    //         'user_details.user_id',
    //         'user_details.dob',
    //         'user_details.gender',
    //         'user_details.bio',
    //     ];

    //     $data = $this->interestService->fetchRecomUser($columns);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Fetched successfully!',
    //         'error' => null,
    //         'data' => $data,
    //     ]);
    // }

    public function fetchRecomUser(Request $request)
    {
        $columns = [
            'users.name',
            'users.username',
            'users.email',
            'user_details.user_id',
            'user_details.dob',
            'user_details.gender',
            'user_details.bio',
        ];

        $perPage = $request->input('per_page', 10); // Default to 10 if not provided
        $page = $request->input('page', 1); // Default to 1 if not provided
        $data = $this->interestService->fetchRecomUser($columns, $perPage, $page);

        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ],
        ]);
    }

    // pagination code of fetchRecomUser
    // public function fetchRecomUser(Request $request)
    // {
    //     $columns = [
    //         'users.name',
    //         'users.username',
    //         'users.email',
    //         'user_details.user_id',
    //         'user_details.dob',
    //         'user_details.gender',
    //         'user_details.bio',
    //     ];

    //     $perPage = $request->input('per_page', 15);
    //     $page = $request->input('page', 1);

    //     $data = $this->interestService->fetchRecomUser($columns, $perPage, $page);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Fetched successfully!',
    //         'error' => null,
    //         'data' => $data,
    //     ]);
    // }
}
