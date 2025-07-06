<?php

use App\Http\Controllers\Backend\Api\v1\AccountController;
use App\Http\Controllers\Backend\Api\v1\AuthController;
use App\Http\Controllers\Backend\Api\v1\ChatMessageController;
use App\Http\Controllers\Backend\Api\v1\InterestController;
use App\Http\Controllers\Backend\Api\v1\PolicyController;
use App\Http\Controllers\Backend\Api\v1\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::post('invitation-sign-up', [AuthController::class, 'signin']);
    Route::post('sign-up-detail', [AuthController::class, 'signup']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('valid-otp', [AuthController::class, 'validOtp']);
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('get-policy', [PolicyController::class, 'fetchPolicy']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('interest-list', [InterestController::class, 'fetchInterestList']);
    Route::get('interest-based-user', [InterestController::class, 'fetchInterestRelatedUser']);
    Route::post('block-user', [AccountController::class, 'blockUser']);
    Route::post('report-user', [AccountController::class, 'reportUser']);
    Route::post('favourite-user', [AccountController::class, 'favouriteUser']);
    Route::post('remove-favourite', [AccountController::class, 'removeFavouriteUser']);
    Route::post('delete-my-account', [AccountController::class, 'deleteMyAccount']);
    Route::get('fetch-user-detail', [AccountController::class, 'userDetails']);
    Route::get('fetch-questions', [AccountController::class, 'fetchQuestions']);
    Route::get('fetch-near-users', [AccountController::class, 'fetchNearUsers']);
    Route::get('fetch-feedback-que', [AccountController::class, 'fetchFeedbackQue']);
    Route::post('user-feedback', [AccountController::class, 'userFeedback']);
    Route::get('get-user-settings', [AccountController::class, 'getUserSettings']);
    Route::post('edit-user-settings', [AccountController::class, 'updateUserSettings']);
    Route::post('edit-user-interest', [AccountController::class, 'updateUserInterest']);
    Route::get('my-interest', [AccountController::class, 'usersInterest']);
    // Route::get('my-cennections', [AccountController::class, 'userConnections']);  //pending to do
    Route::post('edit-user-profile', [AccountController::class, 'updateUserProfle']);
    Route::get('get-user-profile', [AccountController::class, 'getUserProfleData']);
    Route::get('fetch-user-que-ans', [AccountController::class, 'fetchUserQueAns']);
    Route::post('edit-user-que-ans', [AccountController::class, 'updateUserQueAns']);  //need to discuss with shivam for payload data for question_id
    Route::post('user-profile-images', [AccountController::class, 'storeUserProfileImages']);
    Route::get('get-user-profile-images', [AccountController::class, 'getUserProfileImages']);

    Route::post('add-to-my-interest', [AccountController::class, 'addToMyInterest']);
    Route::post('remove-from-my-interest', [AccountController::class, 'removeFromMyInterest']);

    Route::post('send-cennection-request', [AccountController::class, 'sendCennectionReq']);
    // Route::get('pending-req-list', [AccountController::class, 'getPendingReqs']);
    Route::post('accp-reject-request', [AccountController::class, 'acceptRejectReq']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/my-cennections', [AccountController::class, 'getMyConnections']);
    Route::get('/my-favourites', [AccountController::class, 'getMyFavoriteUsers']);
    Route::delete('/delete-connections', [AccountController::class, 'deleteConnection']);

    Route::post('/send-messages', [ChatMessageController::class, 'sendMessage']);
    Route::get('/messages', [ChatMessageController::class, 'getMessages']);
    Route::get('/recent-chat-users', [ChatMessageController::class, 'getRecentChatUsers']);
    Route::get('/recomendation-user', [InterestController::class, 'fetchRecomUser']);
    Route::post('/user-policy', [AccountController::class, 'storeUserPolicy']);

    Route::get('/user-preferences', [AccountController::class, 'userPreferences']);
    Route::post('/user-preferences', [AccountController::class, 'storeUserPreferences']);
    Route::post('/default-user-image', [AccountController::class, 'defaultUserImage']);
    Route::post('/remove-user', [AccountController::class, 'removeUser']);
    Route::get('pending-req-list', [AccountController::class, 'getPendingReqs']);
});
