<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected Collection $products;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test-image.jpg');
        $this->products = Product::factory()->count(3)->create();
        $this->product = $this->products->first();
        $this->productAttributes = Product::factory()->make(['image' => $image])->getAttributes();
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
        $this->actingAs($this->defaultUser)->post(route('admin.products.index'))->assertForbidden();
    }

    public function test_products_show_success(): void
    {
        $this->actingAs($this->adminUser)->get(
            route('admin.products.show', [$this->product->id])
        )->assertOk()->assertSee($this->product->title);
    }

    public function test_products_show_fail(): void
    {
        $this->actingAs($this->defaultUser)->get(
            route('admin.products.show', [$this->product->id])
        )->assertForbidden();
    }

    public function test_products_store_success(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser)->post(
            route('admin.products.store'),
            $this->productAttributes
        )->assertCreated();

        unset($this->productAttributes['image']);
        $this->assertDatabaseHas('products', $this->productAttributes);

        $this->get(route('public.products.index'))->assertJsonFragment($this->productAttributes);
    }

    public function test_products_store_fail(): void
    {
        $this->actingAs($this->defaultUser)->post(
            route('admin.products.store'),
            $this->productAttributes
        )->assertForbidden();

        $this->assertDatabaseMissing('products', $this->productAttributes);

        $this->get(route('public.products.index'))->assertDontSee($this->productAttributes['description']);
    }

    public function test_products_update_success(): void
    {
        $this->product->price++;
        $this->actingAs($this->adminUser)->patch(
            route('admin.products.update', [$this->product->id]),
            ['price' => $this->product->price]
        )->assertOk();

        $this->assertDatabaseHas('products', $this->product->getAttributes());

        $this->get(route('admin.products.show', [$this->product->id]))->assertOk();
    }

    public function test_products_update_fail(): void
    {
        $this->product->price++;
        $this->actingAs($this->defaultUser)->patch(
            route('admin.products.update', [$this->product->id]),
            ['price' => $this->product->price]
        )->assertForbidden();

        $this->assertDatabaseMissing('products', $this->product->getAttributes());

        $this->get(route('public.products.show', [$this->product->id]))->assertDontSee($this->product->price);
    }

    public function test_products_destroy_success(): void
    {
        $this->actingAs($this->adminUser)->delete(
            route('admin.products.destroy', [$this->product->id])
        )->assertOk();
        $this->assertSoftDeleted('products', $this->product->getAttributes());
    }

    public function test_products_destroy_fail(): void
    {
        $this->actingAs($this->defaultUser)->delete(
            route('admin.products.destroy', [$this->product->id])
        )->assertForbidden();
        $this->assertDatabaseHas('products', $this->product->getAttributes());
    }
}
