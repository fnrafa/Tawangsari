<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Carousel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CarouselController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $carousels = Carousel::all();
            return ResponseHelper::Success('Carousels retrieved successfully', $carousels);
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $path = $request->file('image')->store('public/images');

            $carousel = new Carousel();
            $carousel->uuid = (string)Str::uuid();
            $carousel->image_path = $path;
            $carousel->save();

            return ResponseHelper::Created('Carousel created successfully', $carousel);
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
            $carousel = Carousel::where('uuid', $uuid)->firstOrFail();
            return ResponseHelper::Success('Carousel retrieved successfully', $carousel);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return ResponseHelper::NotFound('Carousel not found');
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        try {
            $carousel = Carousel::where('uuid', $uuid)->firstOrFail();

            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                ]);

                Storage::delete($carousel->image_path);

                $path = $request->file('image')->store('public/images');
                $carousel->image_path = $path;
            }

            $carousel->save();

            return ResponseHelper::Success('Carousel updated successfully', $carousel);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Carousel not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy($uuid): JsonResponse
    {
        try {
            $carousel = Carousel::where('uuid', $uuid)->firstOrFail();

            Storage::delete($carousel->image_path);

            $carousel->delete();

            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Carousel not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
