<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddToCart>
 */
class AddToCartFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $products = Product::pluck('id')->toArray();
    $product_id = $this->faker->randomElement($products);
    $product = Product::find($product_id);
    $product_price = $product->price;

    $user = User::pluck('id')->toArray();
    $user_id = $this->faker->randomElement($user);

    $qty = $this->faker->randomDigit();
    $total_price = $qty * $product_price;

    return [
      'product_id' => $product_id,
      'user_id' => $user_id,
      'quantity' => $qty,
      'total_price' => $total_price,
    ];
  }
}
