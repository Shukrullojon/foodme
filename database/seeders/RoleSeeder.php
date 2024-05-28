<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::pluck('id', 'id')->all();
        $admin = Role::create(['name' => 'Admin']);
        $admin->syncPermissions($permissions);
        $teacher = Role::create(['name' => 'Teacher']);
        $teacher->syncPermissions($permissions);
        $reception = Role::create(['name' => 'Reception']);
        $reception->syncPermissions($permissions);
        $finance = Role::create(['name' => 'Finance']);
        $finance->syncPermissions($permissions);
        $student = Role::create(['name' => 'Student']);
        $student->syncPermissions($permissions);
    }
}
