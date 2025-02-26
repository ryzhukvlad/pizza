<?php

namespace Database\Factories;

use App\Enum\ProductType;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_column(ProductType::cases(), 'value');
        $title = $this->faker->unique()->word();
        return [
            'title' => $title,
            'description' => $this->faker->text(),
            'type' => $this->faker->randomElement($types),
            'price' => $this->faker->randomFloat(2, 400, 1000),
            'image' => UploadedFile::fake()->image(
                "$title.jpg", 400, 400
            )->storeAs('images', "$title.jpg", 'public')
        ];
    }
}
