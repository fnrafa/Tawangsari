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
use Illuminate\Support\Facades\Storage;

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
    Route::post('carousels', [CarouselController::class, 'store']);
    Route::post('news', [NewsController::class, 'store']);
    Route::post('umkms', [UmkmController::class, 'store']);
    Route::post('structures', [StructureController::class, 'store']);
    Route::post('galleries', [GalleryController::class, 'store']);
    Route::post('profile', [ProfileController::class, 'store']);

    Route::post('carousels/{carousel}', [CarouselController::class, 'update']);
    Route::post('news/{news}', [NewsController::class, 'update']);
    Route::post('umkms/{umkm}', [UmkmController::class, 'update']);
    Route::post('structures/{structure}', [StructureController::class, 'update']);
    Route::post('galleries/{gallery}', [GalleryController::class, 'update']);
    Route::post('profile/update', [ProfileController::class, 'update']);

    Route::delete('carousels/{carousel}', [CarouselController::class, 'destroy']);
    Route::delete('news/{news}', [NewsController::class, 'destroy']);
    Route::delete('umkms/{umkm}', [UmkmController::class, 'destroy']);
    Route::delete('structures/{structure}', [StructureController::class, 'destroy']);
    Route::delete('galleries/{gallery}', [GalleryController::class, 'destroy']);
    Route::delete('profile', [ProfileController::class, 'destroy']);
});

Route::get('/public/images/{filename}', function ($filename) {
    storage_path('app/public/images/' . $filename);

    if (!Storage::disk('public')->exists('images/' . $filename)) {
        abort(404);
    }

    $file = Storage::disk('public')->get('images/' . $filename);
    $type = Storage::disk('public')->mimeType('images/' . $filename);

    return response($file, 200)->header('Content-Type', $type);
});

Route::fallback(function () {
    return ResponseHelper::NotFound("Route Not found");
});
