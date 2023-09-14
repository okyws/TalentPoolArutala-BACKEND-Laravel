<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
    $category = Categories::pluck('id')->toArray();
    $category_id = $this->faker->randomElement($category);
    $qty = fake()->numberBetween(0, 100);
    $status = ($qty == 0) ? 'out of stock' : 'in stock';

    return [
      'name' => fake()->name(),
      'price' => fake()->numberBetween(10000, 10000000),
      'image' => fake()->imageUrl(),
      'quantity' => $qty,
      'categories_id' => $category_id,
      'status' => $status,
      'description' => fake()->sentence(),
    ];
  }
}
