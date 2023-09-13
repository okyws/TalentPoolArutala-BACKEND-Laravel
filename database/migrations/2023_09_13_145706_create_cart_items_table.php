<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('cart_items', function (Blueprint $table) {
      $table->id();
      $table->foreignId('cart_id')
        ->nullable()
        ->constrained('carts', 'id')
        ->cascadeOnUpdate()
        ->cascadeOnDelete();
      $table->foreignId('product_id')
        ->nullable()
        ->constrained('products', 'id')
        ->cascadeOnUpdate()
        ->nullOnDelete();
      $table->integer('quantity');
      $table->float('subtotal');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cart_items');
  }
};
