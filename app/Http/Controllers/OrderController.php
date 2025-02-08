<?php

namespace App\Http\Controllers;

use App\Enum\UserRole;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return ['orders' => OrderResource::collection(Order::all())];
    }

    public function history(Request $request)
    {
        return response()->json(['orders' => OrderResource::collection(auth('sanctum')->user()->orders)]);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $request->validated();
        $user = auth('sanctum')->user();

        $order = Order::create($order);
        foreach ($user->products as $product) {
            $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        }
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        $user = auth('sanctum')->user();
        if ($order->user_id != $user?->id && $user?->role !== UserRole::ADMIN->value) {
            return response(null, 403);
        }
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return new OrderResource($order);
    }
}
