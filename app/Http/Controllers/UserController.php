<?php

namespace App\Http\Controllers;

use App\Enum\CartLimit;
use App\Http\Requests\CartUserRequest;
use App\Http\Requests\UserAuthRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $user = $request->validated();
        $user['password'] = Hash::make($user['password']);
        return User::create($user)->createToken($user['email'])->plainTextToken;
    }

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

    public function profile(Request $request)
    {
        return auth('sanctum')->user();
    }

    public function storeCart(CartUserRequest $request)
    {
        $cart = $request->validated();
        $user = auth('sanctum')->user();

        $ids = array_column($cart['products'], 'id');
        $quantity = array_column($cart['products'], 'quantity', 'id');

        $products = Product::whereIn('id', $ids)->get();
        $cartTypeCount = [];
        $userProducts = [];
        foreach ($products as $key => $product) {
            if (empty($cartTypeCount[$product->type])) {
                $cartTypeCount[$product->type] = 0;
            }
            $cartTypeCount[$product->type] += $quantity[$product->id];
            $userProducts[$product->id] = ['quantity' => $quantity[$product->id]];
        }

        foreach ($cartTypeCount as $type => $typeQuantity) {
            if ($typeQuantity > CartLimit::typeLimit($type)) {
                return response(['error' => "The quantity of $type product is too many."], 422);
            }
        }
        $user->products()->sync($userProducts);

        return ['Products added to cart successfully.'];
    }

    public function showCart(Request $request)
    {
        $user = auth('sanctum')->user();
        $products = $user->products;
        return ProductResource::collection($products);
    }
}
