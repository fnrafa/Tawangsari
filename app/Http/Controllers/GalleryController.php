<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Gallery;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class GalleryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $galleries = Gallery::all();
            return ResponseHelper::Success('Galleries retrieved successfully', $galleries);
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
            ]);

            $path = $request->file('image')->store('public/images');

            $gallery = new Gallery();
            $gallery->uuid = (string)Str::uuid();
            $gallery->image_path = $path;
            $gallery->save();

            return ResponseHelper::Created('Gallery created successfully', $gallery);
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseHelper::UnprocessableEntity($e->getMessage());
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function show($uuid): JsonResponse
    {
        try {
            $gallery = Gallery::where('uuid', $uuid)->firstOrFail();
            return ResponseHelper::Success('Gallery retrieved successfully', $gallery);
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Gallery not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,heic|max:2048',
            ]);

            $gallery = Gallery::where('uuid', $uuid)->firstOrFail();

            if ($request->hasFile('image')) {
                Storage::delete($gallery->image_path);
                $path = $request->file('image')->store('public/images');
                $gallery->image_path = $path;
            }

            $gallery->save();

            return ResponseHelper::Success('Gallery updated successfully', $gallery);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->errors());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Gallery not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy($uuid): JsonResponse
    {
        try {
            $gallery = Gallery::where('uuid', $uuid)->firstOrFail();
            Storage::delete($gallery->image_path);
            $gallery->delete();

            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Gallery not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}

