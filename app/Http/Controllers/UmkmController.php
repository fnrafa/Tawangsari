<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Umkm;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UmkmController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $umkms = Umkm::all();
            return ResponseHelper::Success('UMKMs retrieved successfully', $umkms);
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'owner' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'google_map_link' => 'required|url',
            ]);

            $path = $request->file('image')->store('public/images');

            $umkm = new Umkm();
            $umkm->uuid = (string)Str::uuid();
            $umkm->title = $request['title'];
            $umkm->description = $request['description'];
            $umkm->owner = $request['owner'];
            $umkm->contact_person = $request['contact_person'];
            $umkm->category = $request['category'];
            $umkm->image_path = $path;
            $umkm->google_map_link = $request['google_map_link'];
            $umkm->save();

            return ResponseHelper::Created('UMKM created successfully', $umkm);
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
            $umkm = Umkm::where('uuid', $uuid)->firstOrFail();
            return ResponseHelper::Success('UMKM retrieved successfully', $umkm);
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('UMKM not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'owner' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'google_map_link' => 'required|url',
            ]);
            $umkm = Umkm::where('uuid', $uuid)->firstOrFail();

            if ($request->hasFile('image')) {
                Storage::delete($umkm->image_path);
                $path = $request->file('image')->store('public/images');
                $umkm->image_path = $path;
            }

            $umkm->title = $request['title'];
            $umkm->description = $request['description'];
            $umkm->owner = $request['owner'];
            $umkm->contact_person = $request['contact_person'];
            $umkm->google_map_link = $request['google_map_link'];
            $umkm->category = $request['category'];
            $umkm->save();

            return ResponseHelper::Success('UMKM updated successfully', $umkm);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('UMKM not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy($uuid): JsonResponse
    {
        try {
            $umkm = Umkm::where('uuid', $uuid)->firstOrFail();
            Storage::delete($umkm->image_path);
            $umkm->delete();
            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Gallery not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
