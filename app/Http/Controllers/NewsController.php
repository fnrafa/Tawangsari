<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\News;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $news = News::all();
            return ResponseHelper::Success('News retrieved successfully', $news);
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $path = $request->file('image')->store('public/images');

            $news = new News();
            $news->uuid = (string)Str::uuid();
            $news->title = $request['title'];
            $news->content = $request['content'];
            $news->image_path = $path;
            $news->uploaded_by = Auth::user()["name"];
            $news->save();

            return ResponseHelper::Created('News created successfully', $news);
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
            $news = News::where('uuid', $uuid)->firstOrFail();
            $recommendations = News::where('uuid', '!=', $uuid)
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();
            return ResponseHelper::Success('News retrieved successfully', [
                'news' => $news,
                'recommendations' => $recommendations,
            ]);
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('News not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            $news = News::where('uuid', $uuid)->firstOrFail();
            if ($request->hasFile('image')) {
                Storage::delete($news->image_path);
                $path = $request->file('image')->store('public/images');
                $news->image_path = $path;
            }
            $news->title = $request['title'];
            $news->content = $request['content'];
            $news->save();

            return ResponseHelper::Success('News updated successfully', $news);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('News not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy($uuid): JsonResponse
    {
        try {
            $news = News::where('uuid', $uuid)->firstOrFail();
            Storage::delete($news->image_path);
            $news->delete();
            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('News not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
