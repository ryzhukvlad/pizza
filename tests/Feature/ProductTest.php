<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $defaultUser;
    protected User $adminUser;
    protected UploadedFile $image;

    protected function setUp(): void
    {
        parent::setUp();
        $this->defaultUser = User::factory()->create();
        $this->adminUser = User::factory()->create(['role' => 'admin']);
        Storage::fake('public');
        $this->image = UploadedFile::fake()->image('test-image.jpg');
    }

    public function test_products_index_success(): void
    {
        $this->get(route('public.products.index'))->assertOk();
    }

    public function test_products_index_fail(): void
    {
        $this->post(route('public.products.index'))->assertMethodNotAllowed();
    }

    public function test_products_show_success(): void
    {
        $product = Product::factory()->create();
        $this->get(route('public.products.show', [$product->id]))->assertOk();
    }

    public function test_products_show_fail(): void
    {
        $this->get(route('public.products.show', [1]))->assertNotFound();
    }

    public function test_products_store_success(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser)->post(
            route('admin.products.store'),
            Product::factory()->make(['image' => $this->image])->getAttributes()
        )->assertCreated();
    }

    public function test_products_store_fail(): void
    {
        $this->actingAs($this->defaultUser)->post(
            route('admin.products.store'),
            Product::factory()->make(['image' => $this->image])->getAttributes()
        )->assertForbidden();
    }

    public function test_products_update_success(): void
    {
        $product = Product::factory()->create();
        $this->actingAs($this->adminUser)->patch(
            route('admin.products.update', [$product->id]),
            ['price' => $product->price + 1]
        )->assertOk();
    }

    public function test_products_update_fail(): void
    {
        $product = Product::factory()->create();
        $this->actingAs($this->defaultUser)->patch(
            route('admin.products.update', [$product->id]),
            $product->only('title')
        )->assertForbidden();
    }
}
