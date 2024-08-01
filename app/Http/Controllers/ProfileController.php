<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Profile;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $profile = Profile::first();
            if ($profile) {
                $profile->image_url = asset('storage/' . $profile->image_path);
                return ResponseHelper::Success('Profile retrieved successfully', $profile);
            }
            return ResponseHelper::NotFound('Profile not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            if (Profile::exists()) {
                return ResponseHelper::Conflict('Profile already exists');
            }
            $path = $request->file('image')->store('public/images');

            $profile = new Profile();
            $profile->uuid = (string)Str::uuid();
            $profile->description = $request['description'];
            $profile->image_path = $path;
            $profile->save();

            return ResponseHelper::Created('Profile created successfully', $profile);
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseHelper::UnprocessableEntity($e->getMessage());
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'description' => 'required|string',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $profile = Profile::firstOrFail();

            if ($request->hasFile('image')) {
                Storage::delete($profile->image_path);
                $path = $request->file('image')->store('public/images');
                $profile->image_path = $path;
            }

            $profile->description = $request['description'];
            $profile->save();

            return ResponseHelper::Success('Profile updated successfully', $profile);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Profile not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy(): JsonResponse
    {
        try {
            $profile = Profile::firstOrFail();
            Storage::delete($profile->image_path);
            $profile->delete();

            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Profile not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
