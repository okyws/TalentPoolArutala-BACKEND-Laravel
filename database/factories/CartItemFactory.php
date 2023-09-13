<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddToCart>
 */
class CartItemFactory extends Factory
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

    $cart = Cart::pluck('id')->toArray();
    $cart_id = $this->faker->randomElement($cart);

    $qty = rand(2, 6);
    $subtotal = $qty * $product_price;

    return [
      'cart_id' => $cart_id,
      'product_id' => $product_id,
      'quantity' => $qty,
      'subtotal' => $subtotal,
    ];
  }
}
