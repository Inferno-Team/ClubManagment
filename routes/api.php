<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Traits\LocalResponse;

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('error403', function () {
    return LocalResponse::returnError('يجب ان تقوم بتسجيل الدخول اولاً', 403);
})->name('login');

Route::post('/register', [UserController::class, 'register']);
Route::get('clubs', [ClubController::class, 'showAllClub'])->prefix('club');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('just-name', [SubscriptionController::class, 'showJustNameSubscription'])->prefix('subscription');
    Route::get('/get-table-ingredient',[TrainerController::class,'getTableIngredient'])->prefix("trainer");

    Route::group(['middleware' => ['is_admin']], function () {
        // Club ( CRUD )

        Route::post('search', [ClubController::class, 'searchClub'])->prefix('club');
        Route::get('/show/club/{id}', [ClubController::class, 'showClub'])->prefix('club');


        Route::post('create_club', [ClubController::class, 'createNewClub'])->prefix('club');
        Route::post('delete_club', [ClubController::class, 'deleteClub']);
        Route::post('edit_club_manager', [ClubController::class, 'editClubManager']);


        // Subscription types (CRUD)
        Route::post('create', [SubscriptionController::class, 'createSubscription'])->prefix('subscription');
        Route::post('delete', [SubscriptionController::class, 'deleteSubscription'])->prefix('subscription');
        Route::post('edit', [SubscriptionController::class, 'updateSubscription'])->prefix('subscription');
        Route::get('all', [SubscriptionController::class, 'showAllSubscription'])->prefix('subscription');
    });

    Route::group(['middleware' => ['is_manager']], function () {

        Route::post('update', [ClubController::class, 'updateClub'])->prefix('club');
        Route::get('my-club', [ClubController::class, 'myClub'])->prefix('club');
        Route::get('my-single-club-subscription/{id}', [ClubController::class, 'mySingleClubSubscription'])->prefix('club');
        Route::post('make-club-subscription', [ClubController::class, 'makeClubSubscription'])->prefix('subscription');
        Route::post('delete-club-subscription', [ClubController::class, 'deleteClubSubscription'])->prefix('subscription');
        Route::get('get-all-subscriptions', [ClubController::class, 'getAllSubscriptions'])->prefix('subscription');
        Route::get('get-all-user-subscriptions', [ClubController::class, 'getAllUsersSubscriptions'])
            ->prefix('subscription');
        Route::post('delete-customer-subscription', [ClubController::class, 'deleteCustomerSubscription'])->prefix('subscription');
        Route::get('get-all-manager-requests', [ClubController::class, 'getAllJoiningRequests']);
        Route::post('approve-customer-subscription', [ClubController::class, 'approveCustomerSub'])->prefix('subscription');
    });

    Route::group(['middleware' => ['is_customer']], function () {
        Route::post('subscribe-to-club', [CustomerController::class, 'subscribeToClub'])->prefix('customer');
        Route::get('/get-all-table', [CustomerController::class, 'getAllTables'])->prefix('customer');
        Route::post('/subscribe-to-diet', [CustomerController::class, 'subscribeToTable'])->prefix('customer');
        Route::post('/un-subscribe-to-diet', [CustomerController::class, 'unSubscribeToTable'])->prefix('customer');
        Route::post('/check-if-subscribed',[CustomerController::class, 'checkIfSubscribed'])->prefix('customer');
        Route::post('/check-if-subscribed-diet',[CustomerController::class, 'checkIfSubscribedDiet'])->prefix('customer');
        Route::get('/get-single-club-subscription', [CustomerController::class, 'singleClubSubscription'])->prefix('customer');
        Route::get('/get-my-diet-subscription', [CustomerController::class, 'getMyDietsSubscriptions'])->prefix('customer');
        Route::get('/get-my-subscription', [CustomerController::class, 'getMySubscriptions']);
        Route::post('/attend',[CustomerController::class, 'makeAttend']);

    });
    Route::group(['middleware' => ['not_customer']], function () {
        Route::get('show/{id}', [SubscriptionController::class, 'showSingleSubscription'])->prefix('subscription');
    });
    Route::group(['middleware' => ['is_trainer']], function () {
        Route::post('/add-table', [TrainerController::class, 'addTable'])->prefix('trainer');
        Route::get('/get-my-tables', [TrainerController::class, 'getAllTables'])->prefix('trainer');
        Route::post('/delete-table', [TrainerController::class, 'deleteTable'])->prefix('trainer');
        Route::post('/add-table-ingredient',[TrainerController::class,'addTableIngredient'])->prefix("trainer");
        Route::post('/delete-table-ingredient',[TrainerController::class,'deleteTableIngredient'])->prefix("trainer");
        Route::post('/update-table-ingredient',[TrainerController::class,'updateTableIngredient'])->prefix("trainer");
    });
});


//  http://127.0.0.1/phpmyadmin/index.php?route=/database/structure&db=club

// protocl + ip address : port number  + path +  ( route | file ) ? GET json { key : value }
