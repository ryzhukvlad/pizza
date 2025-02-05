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

    protected Product $product;
    protected array $productAttributes;
    protected string $table;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test-image.jpg');

        $this->product = Product::factory()->create();
        $this->productAttributes = Product::factory()->make(['image' => $image])->getAttributes();
        $this->table = $this->product->getTable();
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
        $this->get(route('public.products.show', [$this->product->id]))->assertOk();
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
            $this->productAttributes
        )->assertCreated();

        unset($this->productAttributes['image']);
        $this->assertDatabaseHas($this->table, $this->productAttributes);

        $this->get(route('public.products.index'))->assertJsonFragment($this->productAttributes);
    }

    public function test_products_store_fail(): void
    {
        $this->actingAs($this->defaultUser)->post(
            route('admin.products.store'),
            $this->productAttributes
        )->assertForbidden();

        $this->assertDatabaseMissing($this->table, $this->productAttributes);

        $this->get(route('public.products.index'))->assertDontSee($this->productAttributes['description']);
    }

    public function test_products_update_success(): void
    {
        $this->product->price++;
        $this->actingAs($this->adminUser)->patch(
            route('admin.products.update', [$this->product->id]),
            ['price' => $this->product->price]
        )->assertOk();

        $this->assertDatabaseHas($this->table, $this->product->getAttributes());

        $this->get(route('admin.products.show', [$this->product->id]))->assertOk();
    }

    public function test_products_update_fail(): void
    {
        $this->product->price++;
        $this->actingAs($this->defaultUser)->patch(
            route('admin.products.update', [$this->product->id]),
            ['price' => $this->product->price]
        )->assertForbidden();

        $this->assertDatabaseMissing($this->table, $this->product->getAttributes());

        $this->get(route('public.products.show', [$this->product->id]))->assertDontSee($this->product->price);
    }
}
