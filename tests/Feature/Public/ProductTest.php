<?php

namespace Tests\Feature\Public;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create();
    }

    public function test_public_products_index_success(): void
    {
        $this->get(route('public.products.index'))->assertOk();
        $this->get(route('public.products.index'))->assertSee($this->product->description);
    }

    public function test_public_products_index_fail(): void
    {
        $this->post(route('public.products.index'))->assertMethodNotAllowed();
    }

    public function test_products_show_success(): void
    {
        $this->get(route('public.products.show', [$this->product->id]))->assertOk();
        $this->get(route('public.products.index'))->assertSee($this->product->description);
    }

    public function test_products_show_fail(): void
    {
        $this->get(route('public.products.show', [1]))->assertNotFound();
    }
}
