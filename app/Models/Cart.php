<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
  ];

  protected $hidden = [];

  protected $casts = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function cartItems()
  {
    return $this->hasMany(CartItem::class);
  }

  public function getTotal()
  {
    return $this->cartItems()->sum('quantity');
  }

  public function addItem($productId, $quantity)
  {
    $product = Product::find($productId);

    if (!$product) {
      throw new \Exception('Product not found');
    }

    if ($product) {
      $cartItem = $this->cartItems()->where('product_id', $product->id)->first();

      if ($cartItem) {
        $cartItem->quantity += $quantity;
        $cartItem->subtotal = $cartItem->quantity * $product->price;
        $cartItem->save();
      } else {
        $cartItem = new CartItem([
          'product_id' => $product->id,
          'quantity' => $quantity,
          'subtotal' => $product->price * $quantity,
        ]);
        $this->cartItems()->save($cartItem);
      }
    }
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }
}
