<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(10)->create()->each(function (Order $order) {
            $productIds = Product::all()->random(3)->pluck('id')->flip()->toArray();
            $order->products()->attach(
                array_map(fn() => ['quantity' => rand(1, 3)], $productIds)
            );
        });
    }
}
