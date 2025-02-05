<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    protected User $orderUser;
    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderUser = User::factory()->create();
        $this->order = Order::factory(['user_id' => $this->orderUser->id])->create();
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

    public function test_order__success(): void
    {

    }
}
