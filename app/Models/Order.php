<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'cart_id',
    'address',
    'courier_service',
    'delivery_cost',
    'total_item',
    'total_payment',
    'payment_method',
    'status',
  ];

  protected $hidden = [];

  protected $casts = [];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function cart()
  {
    return $this->belongsTo(Cart::class);
  }

  public function orderDetails()
  {
    return $this->hasMany(OrderDetail::class);
  }
}
