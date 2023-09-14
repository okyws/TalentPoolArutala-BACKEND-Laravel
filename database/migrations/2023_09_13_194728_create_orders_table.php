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
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')
        ->nullable()
        ->constrained('users', 'id')
        ->cascadeOnUpdate()
        ->nullOnDelete();
      $table->foreignId('cart_id')
        ->nullable()
        ->constrained('carts', 'id')
        ->nullOnUpdate()
        ->nullOnDelete();
      $table->string('address');
      $table->string('courier_service');
      $table->string('delivery_cost');
      $table->string('total_item');
      $table->double('total_payment', 10, 2);
      $table->string('payment_method');
      $table->string('status');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('orders');
  }
};
