<?php

namespace Tests\Feature\User;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Collection $products;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['email' => 'test@example.com', 'password' => 'password']);
        $this->products = Product::factory()->count(3)->create();
    }

    public function test_user_show_cart_success(): void
    {
        $this->user->products()->attach($this->products, ['quantity' => 3]);
        $this->actingAs($this->user)->get(route('user.cart.show'))->assertOk()->assertSee($this->products[0]->name);
    }

    public function test_user_show_cart_fail(): void
    {
        $this->get(route('user.cart.show'), ['Accept' => 'application/json'])->assertUnauthorized();
    }

    public function test_user_store_cart_success(): void
    {
        $cartData = [];
        foreach ($this->products as $product) {
            $cartData['products'][] = ['id' => $product->id, 'quantity' => 3];
        }
        $this->actingAs($this->user)->post(route('user.cart.store'), $cartData)->assertOk()->assertJson([
            'Products added to cart successfully.'
        ]);
        $rows = array_map(fn($cartProduct) => [
            'user_id' => $this->user->id, 'product_id' => $cartProduct['id'], 'quantity' => 3
        ], $cartData['products']);

        foreach ($rows as $row) {
            $this->assertDatabaseHas('cart_products', $row);
        }
    }

    public function test_user_store_cart_fail(): void
    {
        $cartData = [];
        foreach ($this->products as $product) {
            $cartData['products'][] = ['id' => $product->id, 'quantity' => 3];
        }
        $this->post(route('user.cart.store'), $cartData, ['Accept' => 'application/json'])->assertUnauthorized();
        $rows = array_map(fn($cartProduct) => [
            'user_id' => $this->user->id, 'product_id' => $cartProduct['id'], 'quantity' => 3
        ], $cartData['products']);

        foreach ($rows as $row) {
            $this->assertDatabaseMissing('cart_products', $row);
        }
    }
}
