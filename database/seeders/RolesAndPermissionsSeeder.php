<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run()
  {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'customer']);
    Role::create(['name' => 'seller']);

    Permission::create(['name' => 'create-users']);
    Permission::create(['name' => 'edit-users']);
    Permission::create(['name' => 'delete-users']);
    Permission::create(['name' => 'read-users']);

    $adminRole = Role::findByName('admin');
    $adminRole->syncPermissions([
      'create-users',
      'edit-users',
      'delete-users',
      'read-users',
    ]);
  }
}
