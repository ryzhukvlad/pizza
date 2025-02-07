<?php

namespace Tests\Feature\User;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Collection $products;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email' => 'test@example.com', 'password' => 'password']);
        $this->products = Product::factory()->count(3)->create();
        $this->order = Order::factory(['user_id' => $this->user->id])->create();
        $this->order->products()->attach($this->products, ['quantity' => 3]);
    }

    public function test_user_order_history_success(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.orders.history'))->assertOk();
        foreach ($this->products as $product) {
            $response->assertSee($product->title);
        }
    }

    public function test_user_order_history_fail(): void
    {
        $this->get(route('user.orders.history'), ['Accept' => 'application/json'])->assertUnauthorized();
    }

    public function test_user_order_show_success(): void
    {
        $response = $this->actingAs($this->user)->get(route('user.orders.show', [$this->order->id]))->assertOk();
        foreach ($this->products as $product) {
            $response->assertSee($product->title);
        }
    }

    public function test_user_order_show_fail(): void
    {
        $this->get(route('user.orders.show', $this->order->id), ['Accept' => 'application/json'])->assertUnauthorized();
    }

    public function test_user_order_store_success(): void
    {
        $this->user->products()->attach($this->products, ['quantity' => 3]);
        $orderData = Order::factory()->make()->getAttributes();
        $orderData['time'] = $orderData['time']->format('Y-m-d H:i:s');
        $response = $this->actingAs($this->user)->post(route('user.orders.store'), $orderData)->assertCreated();
        foreach ($this->products as $product) {
            $response->assertSee($product->title);
        }
    }

    public function test_user_order_store_fail(): void
    {
        $this->user->products()->attach($this->products, ['quantity' => 3]);
        $orderData = Order::factory()->make()->getAttributes();
        $orderData['time'] = $orderData['time']->format('Y-m-d H:i:s');
        $this->post(route('user.orders.store'), $orderData, ['Accept' => 'application/json'])->assertUnauthorized();
    }
}
