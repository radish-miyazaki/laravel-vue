<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Influencer\LinkController;
use App\Http\Controllers\Influencer\InfluencerProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// COMMON
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);

Route::group([
    'middleware' => 'auth:api',
], function() {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);
});

// ADMIN
Route::group([
    'middleware' => ['auth:api', 'scope:admin'],
    'prefix' => 'admin',
//    'namespace' => 'Admin',
], function() {
    Route::get('chart', [DashboardController::class, 'chart']);

    Route::post('upload', [ImageController::class, 'upload']);
    Route::get('export', [OrderController::class, 'export']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class)->only('index', 'show');
    Route::apiResource('permissions', PermissionController::class)->only('index');
});

// INFLUENCER
Route::group([
    'prefix' => 'influencer',
//    'namespace' => 'Influencer',
], function() {
    Route::get('products', [InfluencerProductController::class, 'index']);

    Route::group([
        'middleware' => ['auth:api', 'scope:influencer'],
    ], function () {
        Route::post('links', [LinkController::class, 'store']);
        Route::get('stats', [\App\Http\Controllers\Influencer\StatsController::class, 'index']);
        Route::get('rankings', [\App\Http\Controllers\Influencer\StatsController::class, 'rankings']);
    });
});

// CHECKOUT
Route::group([
    'prefix' => 'checkout',
//    'namespace' => 'Checkout',
], function() {
    Route::get('links/{code}', [LinkController::class, 'show']);
    Route::post('orders', [\App\Http\Controllers\Checkout\OrderController::class, 'store']);
    Route::post('orders/confirm', [\App\Http\Controllers\Checkout\OrderController::class, 'confirm']);
});
