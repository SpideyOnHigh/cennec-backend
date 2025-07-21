<?php

namespace App\Http\Controllers\Backend\Api\v1;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\InvitationCodeMaster;
use App\Models\SignInOtp;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserInterest;
use App\Models\UserProfileImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/sign-up-detail",
     *     summary="User Signup",
     *     description="Registers a new user with the provided details.",
     *     operationId="signup",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Details required to sign up a new user",
     *         @OA\JsonContent(
     *             required={"username", "email", "password", "confirm_password", "dob", "gender", "invitation_code"},
     *             @OA\Property(
     *                 property="username",
     *                 type="string",
     *                 example="johndoe"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="johndoe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="password123"
     *             ),
     *             @OA\Property(
     *                 property="confirm_password",
     *                 type="string",
     *                 format="password",
     *                 example="password123"
     *             ),
     *             @OA\Property(
     *                 property="dob",
     *                 type="string",
     *                 format="date",
     *                 example="1990-01-01"
     *             ),
     *             @OA\Property(
     *                 property="gender",
     *                 type="integer",
     *                 enum={1, 2, 3},
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="location",
     *                 type="string",
     *                 example="New York"
     *             ),
     *             @OA\Property(
     *                 property="invitation_code",
     *                 type="string",
     *                 example="INVITE1234"
     *             ),
     *             @OA\Property(
     *                 property="lat",
     *                 type="number",
     *                 format="float",
     *                 example=40.7128
     *             ),
     *             @OA\Property(
     *                 property="lng",
     *                 type="number",
     *                 format="float",
     *                 example=-74.0060
     *             ),
     *             @OA\Property(
     *                 property="fcm_token",
     *                 type="string",
     *                 example="fcm_token_example"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Registration successful"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     type="string",
     *                     example="johndoe"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="John Doe"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     example="johndoe@example.com"
     *                 ),
     *                 @OA\Property(
     *                     property="fcm_token",
     *                     type="string",
     *                     example="fcm_token_example"
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
     *                     type="string",
     *                     example="The email field is required."
     *                 )
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
     *                 example="An error occurred while creating the user."
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

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'name' => 'nullable|string|max:255',
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',          // At least one uppercase letter
                'regex:/[a-z]/',          // At least one lowercase letter
                'regex:/[0-9]/',          // At least one number
                'regex:/[@$!%*?&]/',      // At least one special character
            ],
            'confirm_password' => 'same:password',
            'dob' => 'required|date_format:m-d-Y',
            'gender' => 'required|integer|in:1,2,3',
            'location' => 'nullable|string',
            'invitation_code' => 'required|string|exists:invitation_code_masters,code',
            'invitation_code' => 'nullable|string|exists:invitation_code_masters,code',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'fcm_token' => 'nullable|string',
        ], [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'confirm_password.same' => 'The confirm password must match the password.',
        ]);

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['user_exists' => 'This email is already registered. Please log in or use a different email.'],
                'data' => null,
            ], 422);
        }

        if (User::where('username', $request->username)->exists()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['user_exists' => 'User already exists with this username.'],
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

        DB::beginTransaction();

        try {
            $invitationCode = InvitationCodeMaster::where('code', $request->invitation_code)->first();

            if (! $invitationCode) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['code_expired' => 'Invitation code has expired.'],
                    'data' => null,
                ], 422);
            }

            if ($invitationCode->expiration_date && Carbon::parse($invitationCode->expiration_date)->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['code_expired' => 'Invitation code has expired.'],
                    'data' => null,
                ], 422);
            }

            $existingUser = User::withTrashed()->where('email', $request->email)->first();
            if ($existingUser) {
                if ($existingUser->trashed()) {
                    $existingUser->restore();
                }

                $isDifferent = $existingUser->username !== $request->username ||
                    $existingUser->name !== $request->name ||
                    $existingUser->fcm_token !== $request->fcm_token ||
                    $existingUser->invitation_code_id !== ($invitationCode->id ?? 0) ||
                    !Hash::check($request->password, $existingUser->password);

                $existingUser->update([
                    'username' => $request->username,
                    'name' => $request->name,
                    'contact' => $request->contact ?? '',
                    'fcm_token' => $request->fcm_token,
                    'user_status' => '1',
                    'invitation_code_id' => $invitationCode->id ?? 0,
                    'password' => Hash::make($request->password),
                ]);

                $dob = Carbon::createFromFormat('m-d-Y', $request->dob)->format('Y-m-d');
                UserDetail::updateOrCreate(
                    ['user_id' => $existingUser->id],
                    [
                        'dob' => $dob,
                        'gender' => $request->gender,
                        'location' => $request->location,
                        'location_latitude' => $request->lat,
                        'location_longitude' => $request->lng,
                    ]
                );
                $userData = $existingUser;
            } else {
                $user = User::create([
                    'username' => $request->username,
                    'name' => $request->name,
                    'email' => $request->email,
                    'contact' => $request->contact ?? '',
                    'fcm_token' => $request->fcm_token,
                    'user_status' => '1',
                    'invitation_code_id' => $invitationCode->id ?? 0,
                    'password' => Hash::make($request->password),
                ]);

                $user->assignRole('App User');

                $dob = Carbon::createFromFormat('m-d-Y', $request->dob)->format('Y-m-d');
                UserDetail::create([
                    'user_id' => $user->id,
                    'dob' => $dob,
                    'gender' => $request->gender,
                    'location' => $request->location,
                    'location_latitude' => $request->lat,
                    'location_longitude' => $request->lng,
                ]);
                $userData = $user;
            }

            $token = $userData->createToken('Personal Access Token')->accessToken;

            DB::commit();
            // $userData->profile_pictures = $user->profileImages;
            $userStoredData = UserDetail::where('user_id', $user->id)->first();
            if ($userStoredData) {
                $userData->is_accept_about_us = $userStoredData->is_accept_about_us == 0 ? false : true;
                $userData->is_accept_guidelines = $userStoredData->is_accept_guidelines == 0 ? false : true;
            }
            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'error' => null,
                'data' => [
                    'token' => $token,
                    'userData' => $userData,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'An error occurred while creating the user.'],
                'data' => null,
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/invitation-sign-up",
     *     summary="Sign in with OTP",
     *     description="Generates and sends an OTP to the user's email for sign-in.",
     *     operationId="signin",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Credentials required to sign in",
     *         @OA\JsonContent(
     *             required={"email", "invitation_code"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="user@example.com"
     *             ),
     *             @OA\Property(
     *                 property="invitation_code",
     *                 type="string",
     *                 example="INVITE1234"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP sent successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="otp",
     *                     type="integer",
     *                     example=1234
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
     *                     type="string",
     *                     example="The email field is required."
     *                 )
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
     *                 example="Something went wrong. Please try again later."
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

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            // 'invitation_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['user_exists' => 'This email is already registered. Please log in or use a different email.'],
                'data' => null,
            ], 422);
        }

        $code = InvitationCodeMaster::where('code', $request->invitation_code)->first();
        if (is_null($code)) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'The invitation code is not valid or has expired.'],
                'data' => null,
            ], 422);
        }
        $maxUser = $code->max_user_allow;
        $allotedUser = User::where('invitation_code_id', $code->id)->count();
        if ($allotedUser >= $maxUser) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'The invitation code is not valid or has expired.'],
                'data' => null,
            ], 422);
        }

        $otp = rand(1000, 9999);

        SignInOtp::where('email', $request->email)->delete();

        $signInOtp = SignInOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_on' => now()->addMinutes(10),
        ]);

        $email = $signInOtp->email;

        $type = 0;

        Mail::to($email)->send(new OtpMail($otp, $type, $request->email));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfull',
            'error' => null,
            'data' => ['otp' => $otp],
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User Login",
     *     description="Authenticates a user and provides a personal access token.",
     *     operationId="login",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials for login",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="himanshu.dwivedi@9spl.com"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="Test105*"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Login successful"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="your_personal_access_token_here"
     *                 ),
     *                 @OA\Property(
     *                     property="userData",
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="username",
     *                         type="string",
     *                         example="johndoe"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="John Doe"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string",
     *                         format="email",
     *                         example="johndoe@example.com"
     *                     ),
     *                     @OA\Property(
     *                         property="fcm_token",
     *                         type="string",
     *                         example="fcm_token_example"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The provided credentials are incorrect."
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
     *                     type="string",
     *                     example="The email field is required."
     *                 )
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

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        if (! Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['wrong_credentials' => 'The provided credentials are incorrect.'],
                'data' => null,
            ], 400);
        }

        $user = Auth::user();
        if ($user->user_status == 0) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['wrong_credentials' => 'Your account has been blocked, please contact admin.'],
                'data' => null,
            ], 403);
        }

        if ($user) {
            if ($user->roles->first()->name != 'App User') {
                return response()->json([
                    'success' => false,
                    'message' => null,
                    'error' => ['wrong_credentials' => 'The provided credentials are incorrect.'],
                    'data' => null,
                ], 400);
            } else {
                $userData = User::where('email', $request->email)->first();
                $userDetails = UserDetail::where('user_id', $userData->id)->first();
                $userData->is_accept_about_us = $userDetails->is_accept_about_us == 1 ? true : false;
                $userData->is_accept_guidelines = $userDetails->is_accept_guidelines == 1 ? true : false;
                $userImages = UserProfileImage::where('user_id', $user->id)->get();
                $defaultImage = $userImages->where('is_default', true)->value('image_name');
                $userData->default_profile_picture = concatAppUrl($defaultImage);
                $userData->profile_pictures = $userImages->map(function ($image) {
                    return [
                        'image_id' => $image->id,
                        'image_url' => concatAppUrl($image->image_name),
                        'is_default' => boolval($image->is_default),
                    ];
                });
                $user_interests = UserInterest::select(
                    'user_interests.id',
                    'user_interests.interest_id',
                    'interest_masters.interest_name',
                    'interest_masters.interest_color',
                    'interest_masters.interest_icon'
                )
                    ->join('interest_masters', 'user_interests.interest_id', '=', 'interest_masters.id')
                    ->where('user_interests.user_id', $user->id)
                    ->get();

                $userData->interests = $user_interests;
                $hasInterests = UserInterest::where('user_id', $user->id)->count() > 0;
                $user->tokens()->delete();

                $token = $user->createToken('Personal Access Token');
                $token = $token->accessToken;
                $user->update(['fcm_token' => $request->fcm_token]);
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully!',
                    'error' => null,
                    'data' => [
                        'token' => $token,
                        'userData' => $userData,
                        'has_interests' => $hasInterests,
                    ],
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['wrong_credentials' => 'The provided credentials are incorrect.'],
                'data' => null,
            ], 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/valid-otp",
     *     summary="Validate OTP",
     *     description="Validates the OTP sent to the user's email and returns success or failure response.",
     *     operationId="validOtp",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email and OTP for validation",
     *         @OA\JsonContent(
     *             required={"email", "otp"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="johndoe@example.com"
     *             ),
     *             @OA\Property(
     *                 property="otp",
     *                 type="integer",
     *                 example=1234
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP Verified successful"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or Invalid OTP",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Please insert valid OTP."
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

    public function validOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sign_in_otps,email',
            'otp' => 'required|integer|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $check = SignInOtp::where('email', $request->email)
            ->latest()
            ->first();

        if ($check->otp === $request->otp) {
            return response()->json([
                'success' => true,
                'message' => 'OTP Verified successful',
                'error' => null,
                'data' => null,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => null,
            'error' => ['wrong_otp' => 'Please insert valid OTP.'],
            'data' => null,
        ], 422);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout",
     *     description="Logs out the authenticated user by deleting all their tokens.",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Logged out successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Unauthorized"
     *             )
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        $user = $request->user();
        $request->user()->tokens()->delete();
        $user->update(['fcm_token' => null]);
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'error' => null,
            'data' => []
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/change-password",
     *     summary="Change Password",
     *     description="Changes the password for the authenticated user.",
     *     operationId="changePassword",
     *     tags={"User Management"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Current and new password for changing the user's password",
     *         @OA\JsonContent(
     *             required={"current_password", "new_password"},
     *             @OA\Property(
     *                 property="current_password",
     *                 type="string",
     *                 format="password",
     *                 example="currentPassword123"
     *             ),
     *             @OA\Property(
     *                 property="new_password",
     *                 type="string",
     *                 format="password",
     *                 example="newPassword456",
     *                 description="Must be at least 6 characters long"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Password updated successfully."
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Incorrect current password",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The current password is incorrect."
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
     *                     type="string",
     *                     example="The current password field is required."
     *                 )
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

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ], [
            'new_password.required' => 'The password field is required.',
            'new_password.min' => 'The password must be at least 8 characters.',
            'new_password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
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

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['wrong_password' => 'The current password is incorrect.'],
                'data' => null,
            ], 403);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['same_password' => 'The new password cannot be the same as the current password.'],
                'data' => null,
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
            'error' => null,
            'data' => null,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Send Password Reset Link",
     *     description="Sends an OTP to the user's email for password reset. The OTP is valid for 10 minutes.",
     *     operationId="sendResetLink",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Email address of the user to send the OTP for password reset",
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="johndoe@example.com"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="OTP Has been sent to your mail."
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="otp",
     *                     type="integer",
     *                     example=1234
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or email does not exist",
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
     *                     type="string",
     *                     example="The email field is required."
     *                 )
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

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user->userRole->first()->name !== 'App User') {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['failed' => 'The email address entered is not registered. Please try again.'],
                'data' => null,
            ], 403);
        }
        $otp = rand(1000, 9999);
        $userName = User::where('email', $request->email)->value('username');
        SignInOtp::where('email', $request->email)->delete();

        $signInOtp = SignInOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_on' => now()->addMinutes(10),
        ]);

        $email = $signInOtp->email;
        $type = 1;
        Mail::to($email)->send(new OtpMail($otp, $type, $userName));

        return response()->json([
            'success' => true,
            'message' => 'OTP Has been sent to your mail.',
            'error' => null,
            'data' => null,
        ], 200);
    }

    // public function resetPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'otp' => 'required|string',
    //         'email' => 'required|string|email',
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:8',
    //             'regex:/[A-Z]/',          // At least one uppercase letter
    //             'regex:/[a-z]/',          // At least one lowercase letter
    //             'regex:/[0-9]/',          // At least one number
    //             'regex:/[@$!%*?&]/',      // At least one special character
    //         ],
    //         'confirm_password' => 'same:password',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => formatValidationErrors($validator->errors()),
    //             'data' => []
    //         ], 422);
    //     }

    //     $response = Password::reset($request->only('email', 'password', 'confirm_password'), function ($user, $password) {
    //         $user->password = Hash::make($password);
    //         $user->save();
    //     });

    //     if ($response === Password::PASSWORD_RESET) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Password has been reset successfully.',
    //         ], 200);
    //     }

    //     return response()->json([
    //         'success' => false,
    //         'error' => 'Unable to reset password. Please check your token and try again.',
    //     ], 400);
    // }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset Password",
     *     description="Resets the user's password after verifying the OTP sent to the user's email. Requires the OTP, email, and new password.",
     *     operationId="resetPassword",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="OTP and new password details to reset the user's password",
     *         @OA\JsonContent(
     *             required={"otp", "email", "password"},
     *             @OA\Property(
     *                 property="otp",
     *                 type="string",
     *                 example="123456",
     *                 description="One-time password sent to the user's email."
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 example="johndoe@example.com",
     *                 description="Email of the user whose password is being reset."
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 format="password",
     *                 example="NewPassword123!",
     *                 description="New password for the user. Must meet complexity requirements such as length, uppercase, lowercase, number, and special character."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Password has been reset successfully."
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="null"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="null"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User not found."
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 @OA\Property(
     *                     property="no_user",
     *                     type="string",
     *                     example="User not found."
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="null"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error or incorrect OTP",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="null"
     *             ),
     *             @OA\Property(
     *                 property="error",
     *                 type="object",
     *                 additionalProperties={
     *                     "type": "string"
     *                 }
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="null"
     *             )
     *         )
     *     )
     * )
     */

    public function resetPassword(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'otp' => 'required|string',
        //     'email' => 'required|string|email|exists:users,email',
        //     'password' => [
        //         'required',
        //         'string',
        //         'min:8',
        //         'regex:/[A-Z]/',          // At least one uppercase letter
        //         'regex:/[a-z]/',          // At least one lowercase letter
        //         'regex:/[0-9]/',          // At least one number
        //         'regex:/[@$!%*?&]/',      // At least one special character
        //     ],
        //     'confirm_password' => 'same:password',
        // ]);

        $validator = Validator::make($request->all(), [
            'otp' => 'required|string',
            'email' => 'required|string|email|exists:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',          // At least one uppercase letter
                'regex:/[a-z]/',          // At least one lowercase letter
                'regex:/[0-9]/',          // At least one number
                'regex:/[@$!%*?&]/',      // At least one special character
            ],
            'confirm_password' => 'same:password',
        ], [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must include at least one uppercase letter, one lowercase letter, one number, and one special character.',
            'confirm_password.same' => 'The confirm password must match the password.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => formatValidationErrors($validator->errors()),
                'data' => null,
            ], 422);
        }

        $check = SignInOtp::where('email', $request->email)
            ->latest()
            ->first();

        if (is_null($check) || $check->otp !== $request->otp) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['wrong_otp' => 'The email address or OTP you entered is incorrect. Please try again.'],
                'data' => null,
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => ['no_user' => 'User not found.'],
                'data' => null,
            ], 404);
        }
        SignInOtp::where('email', $request->email)->delete();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password has been reset successfully.',
            'error' => null,
            'data' => null,
        ], 200);
    }
}
