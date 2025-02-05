<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartUserRequest;
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

    public function logout(Request $request)
    {
        auth('sanctum')->user()->currentAccessToken()->delete();
        return ['Token deleted successfully.'];
    }

    public function current(Request $request)
    {
        return auth('sanctum')->user();
    }

    public function cart(CartUserRequest $request)
    {
        $cart = $request->validated();
        $user = auth('sanctum')->user();

        $products = array_column($cart['products'], 'quantity', 'id');
        array_walk($products, function (&$product) {
            $product = ['quantity' => $product];
        });
        $user->products()->sync($products);
    }
}
