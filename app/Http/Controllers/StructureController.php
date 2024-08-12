<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Structure;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StructureController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $structures = Structure::all();

            $structureMap = [];
            foreach ($structures as $structure) {
                $structureMap[$structure->uuid] = $structure;
            }

            $sortedStructures = [];

            $buildHierarchy = function ($structure) use (&$sortedStructures, &$structureMap, &$buildHierarchy) {
                $sortedStructures[] = $structure;
                foreach ($structureMap as $child) {
                    if ($child->upper_level_uuid === $structure->uuid) {
                        $buildHierarchy($child);
                    }
                }
            };

            foreach ($structureMap as $structure) {
                if (is_null($structure->upper_level_uuid)) {
                    $buildHierarchy($structure);
                }
            }

            return ResponseHelper::Success('Structures retrieved successfully', $sortedStructures);
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'level' => 'required|string|max:255',
                'nip' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
                'upper_level_uuid' => 'exists:structures,uuid|nullable',
            ]);

            if (is_null($request["upper_level_uuid"]) && Structure::whereNull('upper_level_uuid')->exists()) {
                return ResponseHelper::Conflict('Only one structure can have a null upper_level_uuid.');
            }

            $path = $request->file('image')->store('public/images');

            $structure = new Structure();
            $structure->uuid = (string)Str::uuid();
            $structure->name = $request['name'];
            $structure->level = $request['level'];
            $structure->nip = $request['nip'];
            $structure->image_path = $path;
            $structure->upper_level_uuid = $request['upper_level_uuid'];
            $structure->save();

            return ResponseHelper::Created('Structure created successfully', $structure);
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
            $structure = Structure::where('uuid', $uuid)->firstOrFail();
            return ResponseHelper::Success('Structure retrieved successfully', $structure);
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Structure not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function update(Request $request, $uuid): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'level' => 'required|string|max:255',
                'nip' => 'required|string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp,heic|max:2048',
                'upper_level_uuid' => 'exists:structures,uuid|nullable',
            ]);

            $structure = Structure::where('uuid', $uuid)->firstOrFail();

            if (is_null($request["upper_level_uuid"]) && Structure::whereNull('upper_level_uuid')->where('uuid', '!=', $uuid)->exists()) {
                return ResponseHelper::Conflict('Only one structure can have a null upper_level_uuid.');
            }
            if ($request->hasFile('image')) {
                Storage::delete($structure->image_path);
                $path = $request->file('image')->store('public/images');
                $structure->image_path = $path;
            }
            $structure->name = $request['name'];
            $structure->level = $request['level'];
            $structure->nip = $request['nip'];
            $structure->upper_level_uuid = $request['upper_level_uuid'];
            $structure->save();

            return ResponseHelper::Success('Structure updated successfully', $structure);
        } catch (ValidationException $e) {
            return ResponseHelper::UnprocessableEntity($e->getMessage());
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Structure not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function destroy($uuid): JsonResponse
    {
        try {
            $structure = Structure::where('uuid', $uuid)->firstOrFail();
            if (is_null($structure->upper_level_uuid)) {
                return ResponseHelper::Conflict('Cannot delete the structure with null upper_level_uuid.');
            }
            Storage::delete($structure->image_path);
            $structure->delete();

            return ResponseHelper::NoContent();
        } catch (ModelNotFoundException) {
            return ResponseHelper::NotFound('Structure not found');
        } catch (Exception $e) {
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
