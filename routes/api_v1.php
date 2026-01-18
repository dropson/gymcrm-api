<?php

declare(strict_types=1);

use App\Http\Controllers\API\V1\Club\ClubContextController;
use App\Http\Controllers\API\V1\Club\ClubController;
use App\Http\Controllers\API\V1\Club\ClubMediaController;
use App\Http\Controllers\API\V1\Profile\SubscribeController;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('verified')->group(function () {});

    Route::prefix('clubs')->group(function () {
        Route::get('/', [ClubController::class, 'index']);
        Route::post('/', [ClubController::class, 'store']);

        Route::prefix('{club:slug}')->group(function () {
            Route::get('/context', ClubContextController::class);
            Route::get('/', [ClubController::class, 'show']);
            Route::post('/', [ClubController::class, 'update']);
            Route::post('/logo', [ClubMediaController::class, 'updateLogo']);
            Route::delete('/logo', [ClubMediaController::class, 'deleteLogo']);
        });
    });

    Route::get('/me', function (Request $request) {
        return new UserResource($request->user());
    });

    Route::prefix('profile')->group(function () {
        Route::post('/subscription', [SubscribeController::class, 'update']);
        // Route::get('/', [ProfileController::class, 'show']);
        // Route::post('/update', [ProfileController::class, 'updateProfile']);
        // Route::post('/avatar', [ProfileController::class, 'setAvatar']);
        // Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
        // Route::post('/banner', [ProfileController::class, 'setBanner']);
        // Route::delete('/banner', [ProfileController::class, 'deleteBanner']);
    });
});
