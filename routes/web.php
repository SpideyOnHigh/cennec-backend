<?php

use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\Backend\CategoryInterestController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EmailTestController;
use App\Http\Controllers\Backend\FeedbackController;
use App\Http\Controllers\Backend\InvitationCodeController;
use App\Http\Controllers\Backend\ReportsController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PolicyController;
use App\Http\Controllers\Backend\StaffController;
use App\Http\Controllers\Backend\UserWhitelistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\Backend\ImportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');


Route::prefix('import')->name('import.')->group(function () {
    Route::get('/drinker', [ImportController::class, 'drinker'])->name('drinker');
    Route::get('/smoke', [ImportController::class, 'smoke'])->name('smoke');
    Route::get('/invitation_codes', [ImportController::class, 'invitation_codes'])->name('invitation_codes');
    Route::get('/users', [ImportController::class, 'users'])->name('users');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    /* Dashboard */
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/user-registrations', [DashboardController::class, 'userRegistrations']);
    Route::middleware(['checkRole:Admin'])->group(function () {

        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('/fetch', [RoleController::class, 'fetch'])->name('fetch');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::post('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('staff')->name('staff.')->group(function () {
            Route::get('/', [StaffController::class, 'index'])->name('index');
            Route::post('/fetch', [StaffController::class, 'fetch'])->name('fetch');
            Route::get('/create', [StaffController::class, 'create'])->name('create');
            Route::post('/', [StaffController::class, 'store'])->name('store');
            Route::get('/{staff}/edit', [StaffController::class, 'edit'])->name('edit');
            Route::post('/{staff}', [StaffController::class, 'update'])->name('update');
            Route::delete('/{staff}', [StaffController::class, 'destroy'])->name('destroy');
            Route::post('/update-status/{userId}', [StaffController::class, 'updateStatus'])->name('update-status');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/fetch', [UserController::class, 'fetch'])->name('fetch');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::get('/{user}/show', [UserController::class, 'show'])->name('show');
            Route::post('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportsController::class, 'index'])->name('index');
            Route::post('/fetch', [ReportsController::class, 'fetch'])->name('fetch');
        });

        Route::prefix('feedback')->name('feedback.')->group(function () {
            Route::get('/', [FeedbackController::class, 'index'])->name('index');
            Route::post('/fetch', [FeedbackController::class, 'fetch'])->name('fetch');

            Route::get('/rating', [FeedbackController::class, 'ratingIndex'])->name('rating-index');
            Route::post('/fetch-rating', [FeedbackController::class, 'fetchRating'])->name('fetch-rating');
            Route::get('/graph', [FeedbackController::class, 'ratingGraph']);
        });

        Route::prefix('invitation-codes')->name('invitation-codes.')->group(function () {
            Route::get('/', [InvitationCodeController::class, 'index'])->name('index');
            Route::post('/fetch', [InvitationCodeController::class, 'fetch'])->name('fetch');
            Route::get('/create', [InvitationCodeController::class, 'create'])->name('create');
            Route::post('/', [InvitationCodeController::class, 'store'])->name('store');
        });

        Route::prefix('policy-page')->name('policy-page.')->group(function () {
            Route::get('/', [PolicyController::class, 'index'])->name('index');
            Route::post('/fetch', [PolicyController::class, 'fetch'])->name('fetch');

            Route::get('/{policy}/edit', [PolicyController::class, 'edit'])->name('edit');
            Route::post('/{policy}', [PolicyController::class, 'update'])->name('update');
            // Route::delete('/{policy}', [PolicyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('email-test')->name('email-test.')->group(function () {
            Route::get('/', [EmailTestController::class, 'index'])->name('index');
            Route::post('/send-email', [EmailTestController::class, 'sendEmail'])->name('send-email');
        });

        Route::prefix('interest')->name('interest.')->group(function () {
            Route::get('/', [CategoryInterestController::class, 'index'])->name('index');
            Route::post('/fetch', [CategoryInterestController::class, 'fetch'])->name('fetch');
            Route::get('/create', [CategoryInterestController::class, 'create'])->name('create');
            Route::post('/', [CategoryInterestController::class, 'store'])->name('store');
            Route::get('/{interest}/edit', [CategoryInterestController::class, 'edit'])->name('edit');
            Route::post('/{interest}', [CategoryInterestController::class, 'update'])->name('update');
            Route::delete('/{interest}', [CategoryInterestController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('user-whitelist')->name('user-whitelist.')->group(function () {
            Route::get('/', [UserWhitelistController::class, 'index'])->name('index');
            Route::post('/fetch-domain', [UserWhitelistController::class, 'fetchDomains'])->name('fetch-domain');
            Route::post('/fetch-emails', [UserWhitelistController::class, 'fetchEmails'])->name('fetch-email');
            Route::get('/create', [UserWhitelistController::class, 'create'])->name('create');
            Route::post('/', [UserWhitelistController::class, 'store'])->name('store');
            Route::get('/{userWhitelist}/edit', [UserWhitelistController::class, 'edit'])->name('edit');
            Route::post('/{userWhitelist}', [UserWhitelistController::class, 'update'])->name('update');
            Route::delete('/{userWhitelist}', [UserWhitelistController::class, 'destroy'])->name('destroy');
        });
    });
});

Route::get('/page-not-found', [HomeController::class, 'pageNotFound'])->name('page-not-found');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('{any}', [HomeController::class, 'index'])->name('index');
});
