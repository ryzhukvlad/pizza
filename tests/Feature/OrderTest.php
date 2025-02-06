<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    protected User $orderUser;
    protected Order $order;
    protected Collection $products;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderUser = User::factory()->create();
        $this->products = Product::factory()->count(3)->create();
        $this->order = Order::factory(['user_id' => $this->orderUser->id])->create();
        $this->order->products()->sync($this->products);
    }

    public function test_order_index_success(): void
    {
        $this->actingAs($this->adminUser)->get(route('admin.orders.index'))->assertOk();
    }

    public function test_order_index_fail(): void
    {
        $this->actingAs($this->defaultUser)->get(route('admin.orders.index'))->assertForbidden();
    }

    public function test_order_show_success(): void
    {
        $this->actingAs($this->adminUser)->get(route('admin.orders.show', [$this->order->id]))->assertOk();
        $this->actingAs($this->orderUser)->get(route('user.orders.show', [$this->order->id]))->assertOk();
    }

    public function test_order_show_fail(): void
    {
        $this->actingAs($this->defaultUser)->get(route('admin.orders.show', [$this->order->id]))->assertForbidden();
    }

    public function test_order_store_success(): void
    {
//        $products = [];
//        foreach ($productIds as $productId) {
//            $products[$productId] = ['quantity' => rand(1, 3)];
//        }
//        $this->actingAs($this->defaultUser)->post(
//            route('admin.orders.store'),
//            ['products' => array_co]
//        );
    }
}
