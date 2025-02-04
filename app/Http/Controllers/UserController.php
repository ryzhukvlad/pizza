<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(UserAuthRequest $request)
    {
        if (Auth::once($request->validated())) {
            $user = auth()->user();
            return $user->createToken($user->email)->plainTextToken;
        }

        return response(['error' => 'The provided credentials do not match our records.'], 401);
    }

    public function logout()
    {
        auth('sanctum')->user()->currentAccessToken()->delete();
        return ['Token deleted successfully.'];
    }

    public function current()
    {
        return auth('sanctum')->user();
    }
}
