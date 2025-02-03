<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_products_index_success(): void
    {
        $this->get(route('products.index'))->assertOk();
    }

    public function test_products_index_fail(): void
    {
        $this->post(route('products.index'))->assertForbidden();
    }

    public function test_products_show_success(): void
    {
        $product = Product::factory()->create();
        $this->get(route('products.show', [$product->id]))->assertOk();
    }

    public function test_products_show_fail(): void
    {
        $this->get(route('products.show', [1]))->assertNotFound();
    }

    public function test_products_store_success(): void
    {
        $this->actingAs(
            User::factory()->create(['role' => 'admin'])
        )->post(
            route('products.store'),
            Product::factory()->make()->getAttributes()
        )->assertOk();
    }

    public function test_products_store_fail(): void
    {
        $this->post(
            route('products.store'),
            Product::factory()->make()->getAttributes()
        )->assertForbidden();
    }

    public function test_products_update_success(): void
    {
        $product = Product::factory()->create();
        $this->actingAs(
            User::factory()->create(['role' => 'admin'])
        )->patch(
            route('products.update', [$product->id]),
            ['price' => $product->price + 1]
        )->assertOk();
    }

    public function test_products_update_fail(): void
    {
        $product = Product::factory()->create();
        $this->actingAs(
            User::factory()->create(['role' => 'admin'])
        )->patch(
            route('products.update', [$product->id]),
            $product->only('title')
        )->assertForbidden();
    }
}
