<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Traits\LocalResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('error403', function () {
    return LocalResponse::returnError('يجب ان تقوم بتسجيل الدخول اولاً', 400);
})->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['middleware' => ['is_admin']], function () {
        // Club ( CRUD )
        Route::post('create', [ClubController::class, 'createNewClub'])->prefix('club');
        Route::post('delete', [ClubController::class, 'deleteClub'])->prefix('club');

        Route::post('search', [ClubController::class, 'searchClub'])->prefix('club');
        Route::get('/show/{id}', [ClubController::class, 'showClub'])->prefix('club');
        Route::get('/all', [ClubController::class, 'showAllClub'])->prefix('club');

        // Subscription types (CRUD)
        Route::post('create', [SubscriptionController::class, 'createSubscription'])->prefix('subscription');
        Route::post('delete', [SubscriptionController::class, 'deleteSubscription'])->prefix('subscription');
        Route::post('update', [SubscriptionController::class, 'updateSubscription'])->prefix('subscription');
        Route::get('/all', [SubscriptionController::class, 'showAllSubscription'])->prefix('subscription');
    });
    Route::group(['middleware' => ['is_manager']], function () {
        Route::post('update', [ClubController::class, 'updateClub'])->prefix('club');
        Route::post('make-club-subscription', [
            ClubController::class,
            'makeClubSubscription'
        ])->prefix('subscription');
        Route::post('delete-club-subscription', [ClubController::class, 'deleteClubSubscription'])->prefix('subscription');
        Route::get('get-all-subscriptions', [ClubController::class, 'getAllSubscriptions'])->prefix('subscription');
        Route::get('get-all-user-subscriptions', [ClubController::class, 'getAllUsersSubscriptions'])
            ->prefix('subscription');
    });

    Route::group(['middleware' => ['is_customer']], function () {
        Route::post('subscribe-to-club', [CustomerController::class, 'subscribeToClub'])->prefix('customer');
    });
});
