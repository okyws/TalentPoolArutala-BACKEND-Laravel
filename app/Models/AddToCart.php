<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
  use HasFactory;

  protected $fillable = [
    'product_id',
    'user_id',
    'quantity',
    'total_price',
  ];

  protected $hidden = [];

  protected $casts = [];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
