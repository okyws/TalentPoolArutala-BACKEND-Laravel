<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $users = User::pluck('id')->toArray();
    $user_id = $this->faker->randomElement($users);
    $cart = Cart::where('user_id', $user_id)->pluck('id')->toArray();
    $cart_id = $this->faker->randomElement($cart);
    $total_item = CartItem::where('cart_id', $cart_id)->sum('quantity');
    $subtotal = CartItem::where('cart_id', $cart_id)->sum('subtotal');
    $delivery_cost = 0;
    $courier_service = 'jne';

    switch ($courier_service) {
      case 'jne':
        $delivery_cost = 10000;
        break;
      case 'tiki':
        $delivery_cost = 13000;
        break;
      case 'pos':
        $delivery_cost = 15000;
        break;
      case 'jnt':
        $delivery_cost = 9000;
        break;
      case 'sicepat':
        $delivery_cost = 11000;
        break;
      default:
        $delivery_cost = 10000;
        break;
    }

    $total_payment = $subtotal + $delivery_cost;

    return [
      'user_id' => $user_id,
      'cart_id' => $cart_id,
      'address' => fake()->address,
      'courier_service' => fake()->randomElement([
        'jne',
        'tiki',
        'pos',
        'jnt',
        'sicepat'
      ]),
      'delivery_cost' => $delivery_cost,
      'total_item' => $total_item,
      'total_payment' => $total_payment,
      'payment_method' => fake()->randomElement([
        'cod',
        'credit',
        'debit',
        'e-money',
        'transfer'
      ]),
      'status' => fake()->randomElement([
        'pending',
        'paid',
        'cancelled',
        'shipped',
        'delivered'
      ])
    ];
  }
}
