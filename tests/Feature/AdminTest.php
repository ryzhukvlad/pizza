<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderUser = User::factory()->create();
        $this->products = Product::factory()->count(3)->create();
        $this->order = Order::factory(['user_id' => $this->orderUser->id])->create();
        $this->order->products()->attach($this->products, ['quantity' => 3]);
    }

    public function test_products_index_success(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.index'))->assertOk();
        foreach ($this->products as $product) {
            $response->assertSee($product->title);
        }
    }

    public function test_products_index_fail(): void
    {
        $response = $this->actingAs($this->defaultUser)->get(route('admin.products.index'))->assertForbidden();
    }

    public function test_order_index_success(): void
    {
        $this->actingAs($this->adminUser)->get(
            route('admin.orders.index')
        )->assertOk()->assertSee($this->orderUser->id);
    }

    public function test_order_index_fail(): void
    {
        $this->actingAs($this->defaultUser)->get(route('admin.orders.index'))->assertForbidden();
    }

    public function test_order_show_success(): void
    {
        $this->actingAs($this->adminUser)->get(
            route('admin.orders.show', [$this->order->id])
        )->assertOk()->assertSee($this->orderUser->id);
    }

    public function test_order_show_fail(): void
    {
        $this->actingAs($this->defaultUser)->get(
            route('admin.orders.show', [$this->order->id])
        )->assertForbidden();
    }

    public function test_order_store_success(): void
    {
        $orderData = Order::factory()->make(['user_id' => $this->defaultUser->id]);
        $this->actingAs($this->defaultUser)->post(
            route('admin.orders.show'),
            $orderData->getAttributes()
        )->assertOk();

        $this->assertDatabaseHas($orderData->getTable(), $orderData);
    }

    public function test_order_store_fail(): void
    {

    }
}
