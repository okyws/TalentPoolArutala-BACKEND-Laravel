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
    Schema::create('check_outs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('cart_id')
        ->nullable()
        ->constrained('add_to_carts', 'id')
        ->cascadeOnUpdate()
        ->nullOnDelete();
      $table->string('payment_method');
      $table->integer('quantity');
      $table->integer('total_price');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('check_outs');
  }
};
