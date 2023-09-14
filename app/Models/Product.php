<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'price',
    'image',
    'quantity',
    'categories_id',
    'status',
    'description',
  ];

  protected $casts = [];

  protected $hidden = [];

  public function categories()
  {
    return $this->belongsTo(Categories::class);
  }

  public function cartItem()
  {
    return $this->hasMany(CartItem::class);
  }

  public function OrderDetail()
  {
    return $this->hasMany(OrderDetail::class);
  }
}
