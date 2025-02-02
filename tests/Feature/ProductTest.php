<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_products_successful(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertOk();
    }

    public function test_products_fail(): void
    {
        $response = $this->post(route('products.index'));
        $response->assertFound();
    }

    public function test_products_store_successful(): void
    {
        $response = $this->post(
            route('products.store'),
            Product::factory()->make()
        );
        $response->assertOk();
    }

    public function test_products_store_fail(): void
    {

    }
}
