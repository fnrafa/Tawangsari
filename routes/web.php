<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    return response()->view('errors.404', [], 404);
});
