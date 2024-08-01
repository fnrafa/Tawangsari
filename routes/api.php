<?php

use App\Helpers\ResponseHelper;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

Route::get('carousels', [CarouselController::class, 'index']);
Route::get('carousels/{uuid}', [CarouselController::class, 'show']);
Route::get('news', [NewsController::class, 'index']);
Route::get('news/{uuid}', [NewsController::class, 'show']);
Route::get('umkms', [UmkmController::class, 'index']);
Route::get('umkms/{uuid}', [UmkmController::class, 'show']);
Route::get('structures', [StructureController::class, 'index']);
Route::get('structures/{uuid}', [StructureController::class, 'show']);
Route::get('profile', [ProfileController::class, 'index']);
Route::get('galleries', [GalleryController::class, 'index']);
Route::get('galleries/{uuid}', [GalleryController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('carousels', CarouselController::class)->except(['index', 'show']);
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    Route::apiResource('umkms', UmkmController::class)->except(['index', 'show']);
    Route::apiResource('structures', StructureController::class)->except(['index', 'show']);
    Route::apiResource('galleries', GalleryController::class)->except(['index', 'show']);

    Route::post('profile', [ProfileController::class, 'store']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::delete('profile', [ProfileController::class, 'destroy']);
});

Route::fallback(function () {
    return ResponseHelper::NotFound("Route Not found");
});
