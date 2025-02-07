<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    protected User $orderUser;
    protected Order $order;
    protected function setUp(): void
    {
        parent::setUp();
        $this->orderUser = User::factory()->create();
        $this->order = Order::factory(['user_id' => $this->orderUser->id])->create();
        $this->order->products()->attach(Product::factory()->count(3)->create(), ['quantity' => 3]);
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

    public function test_order_update_success(): void
    {
        $this->order->total++;
        $this->actingAs($this->adminUser)->patch(
            route('admin.orders.update', [$this->order->id]),
            ['total' => $this->order->total]
        )->assertOk();

        $this->assertDatabaseHas('orders', $this->order->getAttributes());

        $this->get(route('admin.orders.show', [$this->order->id]))->assertOk();
    }

    public function test_order_update_fail(): void
    {
        $this->order->total++;
        $this->actingAs($this->defaultUser)->patch(
            route('admin.orders.update', [$this->order->id]),
            ['total' => $this->order->total]
        )->assertForbidden();

        $this->assertDatabaseMissing('orders', $this->order->getAttributes());
    }
}
