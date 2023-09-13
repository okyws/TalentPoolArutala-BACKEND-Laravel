<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CategoriesFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $name = [
      'Electronics',
      'Clothing',
      'Home Appliances',
      'Food',
      'Health',
      'Automotive',
      'Toys',
      'Beauty',
      'Hobbies',
      'Sports',
      'Books',
      'Music',
      'Jewelry',
      'Furniture',
      'Computers',
      'Mobile Phones',
      'Accessories',
      'Cameras',
      'Shoes',
      'Bags',
    ];

    $lowercaseNames = array_map('strtolower', $name);

    return [
      'name' => fake()->unique()->randomElement($lowercaseNames),
      'description' => fake()->sentence(),
    ];
  }
}
