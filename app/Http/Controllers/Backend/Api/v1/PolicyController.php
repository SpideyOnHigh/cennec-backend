<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\GeneralService;
use Illuminate\Http\Request;
use Validator;

class PolicyController extends Controller
{
    public $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    /**
     * @OA\Get(
     *     path="/api/get-policy",
     *     summary="Fetch a policy by slug",
     *     tags={"Policies"},
     *     description="Retrieve the policy details based on the provided slug.",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="The slug of the policy to retrieve.",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Policy fetched successfully",
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
     *                 property="error",
     *                 type="string",
     *                 example="null"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 additionalProperties=true
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Policy not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Policy not found."
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Policy not found"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="null"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Invalid request parameters."
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Invalid slug format"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="null"
     *             )
     *         )
     *     )
     * )
     */
    public function fetchPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $poicy = $this->generalService->getPolicy($request->slug);
        return response()->json([
            'success' => true,
            'message' => 'Fetched successfully!',
            'error' => null,
            'data' => $poicy,
        ]);
    }
}
