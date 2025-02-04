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

    public function store(StoreOrderRequest $request)
    {
        Gate::authorize('store');
        $order = $request->validated();
        $products = $order['products'];
        unset($order['products']);

        $order = Order::create($order);
        $order->products()->attach($products);
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
