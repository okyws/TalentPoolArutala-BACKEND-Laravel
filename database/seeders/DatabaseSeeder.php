<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OrderDetail;
use App\Models\User;
use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([RolesAndPermissionsSeeder::class]);

    User::factory(10)->create();

    User::factory()->create([
      'name' => 'Admin',
      'email' => 'admin@example.com',
    ])->assignRole('admin');

    User::factory()->create([
      'name' => 'Oky Wahyu Setyaji',
      'email' => 'oky@example.com',
    ])->assignRole('admin');

    User::factory()->create([
      'name' => 'Seller',
      'email' => 'seller@example.com',
    ])->assignRole('seller');

    User::factory()->create([
      'name' => 'Customer',
      'email' => 'customer@example.com',
    ]);

    $this->call([
      CategorySeeder::class,
      ProductSeeder::class,
      CartSeeder::class,
      CartItemSeeder::class,
      OrderSeeder::class,
    ]);

    OrderDetail::factory(200)->create();
  }
}
