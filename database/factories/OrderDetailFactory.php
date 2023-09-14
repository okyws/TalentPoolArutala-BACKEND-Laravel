<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'order_id' => fake()->numberBetween(1, 60),
      'product_id' => fake()->numberBetween(1, 90),
      'quantity' => fake()->numberBetween(1, 25),
      'subtotal' => fake()->numberBetween(15000, 15000000),
    ];
  }
}
