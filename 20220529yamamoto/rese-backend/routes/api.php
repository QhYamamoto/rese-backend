<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;

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
    Route::post('/admin/representatives', [AdminController::class, 'registerRepresentative']);
});

/* 店舗代表者権限 */
Route::middleware(['auth:sanctum', 'abilities:shop-owner'])->group(function () {
    Route::prefix('/admin/shops')->group(function () {
        Route::get('/{representative_id}', [AdminController::class, 'getMyShops']);
        Route::get('/{id}/{representative_id}', [AdminController::class, 'getShopDetail']);
        Route::post('', [AdminController::class, 'registerShop']);
        Route::put('/{id}', [AdminController::class, 'updateShop']);
    });
    Route::prefix('admin/courses')->group(function () {
        Route::post('', [AdminController::class, 'registerCourse']);
        Route::delete('/{id}', [AdminController::class, 'destroyCourse']);
    });
    Route::put('/reservations/visit/{id}', [ReservationController::class, 'completeVisit']);
});

/* 一般権限(認証のみ) */
Route::middleware('auth:sanctum')->group(function () {
    /* ユーザー */
    Route::prefix('/auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'me']);
    });

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

/* 認証ガードなし */
/* ユーザー */
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

/* 店舗 */
Route::prefix('/shops')->group(function () {
    Route::get('', [ShopController::class, 'index']);
    Route::get('/{id}', [ShopController::class, 'getById']);
});
