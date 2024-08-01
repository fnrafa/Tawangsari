<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'username' => 'required|unique:users|min:6|max:12',
                'password' => 'required|confirmed|min:6',
            ]);

            $user = new User();
            $user->name = $request['name'];
            $user->username = $request['username'];
            $user->password = Hash::make($request['password']);
            $user->save();

            return ResponseHelper::Created('Success Create new Account', ['user' => $user]);
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseHelper::UnprocessableEntity($e->getMessage());
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'username' => 'required|string|min:6|max:12',
                'password' => 'required|string|min:6',
            ]);

            $credentials = $request->only('username', 'password');
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return ResponseHelper::Unauthorized('Invalid username or password');
            }
            return ResponseHelper::Success('Login successful', ['user' => Auth::user(), 'token' => $token]);
        } catch (Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseHelper::UnprocessableEntity($e->getMessage());
            }
            return ResponseHelper::InternalServerError($e->getMessage());
        }
    }
}
