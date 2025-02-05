<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('index', Order::class);
        return ['orders' => OrderResource::collection(Order::all())];
    }

    public function history(Request $request)
    {
        Gate::authorize('history', Order::class);
        return ['orders' => OrderResource::collection(auth('sanctum')->user()->orders)];
    }

    public function store(StoreOrderRequest $request)
    {
        Gate::authorize('store', Order::class);
        $order = $request->validated();
        $user = auth('sanctum')->user();

        $order = Order::create($order);
        $order->products()->attach($user->products);
        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        Gate::authorize('show', $order);
        return new OrderResource($order);
    }

    public function update(Request $request, Order $order)
    {
        Gate::authorize('update', $order);
        $order->update($request->validated());
        return new OrderResource($order);
    }
}
