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
    $qty = $this->faker->randomDigit();

    return [
      'name' => fake()->name(),
      'price' => fake()->randomFloat(2, 1, 10000),
      'image' => fake()->imageUrl(),
      'quantity' => $qty,
      'categories_id' => $category_id,
      'status' => fake()->randomElement(['instock', 'outofstock']),
      'description' => fake()->sentence(),
    ];
  }
}
