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

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function addToCart()
  {
    return $this->hasMany(AddToCart::class);
  }
}
