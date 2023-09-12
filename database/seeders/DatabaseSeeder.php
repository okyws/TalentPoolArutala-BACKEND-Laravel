<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    User::factory(10)->create();

    User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@example.com',
    ]);

    User::factory()->create([
      'name' => 'Oky Wahyu Setyaji',
      'email' => 'oky@example.com',
    ]);

    User::factory()->create([
      'name' => 'Seller',
      'email' => 'seller@example.com',
    ]);

    User::factory()->create([
      'name' => 'Customer',
      'email' => 'customer@example.com',
    ]);
  }
}
