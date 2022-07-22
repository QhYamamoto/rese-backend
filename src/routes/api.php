<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;

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

/* 認証ガードあり */
/* 管理者権限 */
Route::middleware(['auth:sanctum', 'abilities:administrator'])->group(function () {
    Route::post('/admin/register', [AdminController::class, 'registerRepresentative']);
});

/* 店舗代表者権限 */
Route::middleware(['auth:sanctum', 'abilities:shop-owner'])->group(function () {
    Route::prefix('/admin/shops')->group(function () {
        Route::get('/{representative_id}', [AdminController::class, 'getShopsByRepresentativeId']);
        Route::get('/{id}/{representative_id}', [AdminController::class, 'getShopDetail']);
        Route::post('', [AdminController::class, 'registerShop']);
        Route::put('/{id}', [AdminController::class, 'updateShop']);
    });
    Route::prefix('courses')->group(function () {
        Route::post('', [CourseController::class, 'register']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
    });
    Route::put('/reservations/visit/{id}', [ReservationController::class, 'completeVisit']);
});

/* 一般権限 */
Route::middleware('auth:sanctum')->group(function () {
    /* ユーザー */
    Route::get('/auth/user', [AuthController::class, 'me']);

    /* 店舗 */
    Route::get('shops/favorites/{user_id}', [ShopController::class, 'getFavoriteShops']);

    /* お気に入り */
    Route::prefix('/favorites')->group(function () {
        Route::post('', [FavoriteController::class, 'register']);
        Route::delete('/{user_id}/{shop_id}', [FavoriteController::class, 'destroy']);
    });

    /* 予約 */
    Route::prefix('/reservations')->group(function () {
        Route::get('/{id}', [ReservationController::class, 'getById']);
        Route::get('/user/{user_id}', [ReservationController::class, 'getByUserId']);
        Route::post('', [ReservationController::class, 'register']);
        Route::put('/{id}', [ReservationController::class, 'update']);
        Route::delete('/{id}', [ReservationController::class, 'destroy']);
        Route::post('/pay/{id}', [ReservationController::class, 'pay']);
    });

    /* レビュー */
    Route::prefix('/reviews')->group(function () {
        Route::post('', [ReviewController::class, 'register']);
        Route::put('/{id}', [ReviewController::class, 'update']);
        Route::delete('/{id}', [ReviewController::class, 'destroy']);
    });
});

Route::middleware('auth:sanctum', 'web')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

/* 認証ガードなし */
/* ユーザー */
Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');
});

/* 店舗 */
Route::prefix('/shops')->group(function () {
    Route::get('', [ShopController::class, 'index']);
    Route::get('/{id}', [ShopController::class, 'getById']);
});
