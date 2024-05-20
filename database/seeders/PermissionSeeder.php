<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionList = [
            // home page
            ['name' => 'home-show'],
            // permission page
            ['name' => 'permission-show'],
            ['name' => 'permission-index'],
            ['name' => 'permission-create'],
            ['name' => 'permission-store'],
            ['name' => 'permission-edit'],
            ['name' => 'permission-update'],
            ['name' => 'permission-destroy'],
            // role page
            ['name' => 'role-show'],
            ['name' => 'role-index'],
            ['name' => 'role-create'],
            ['name' => 'role-store'],
            ['name' => 'role-edit'],
            ['name' => 'role-update'],
            ['name' => 'role-destroy'],
            // user page
            ['name' => 'user-show'],
            ['name' => 'user-index'],
            ['name' => 'user-create'],
            ['name' => 'user-store'],
            ['name' => 'user-edit'],
            ['name' => 'user-update'],
            ['name' => 'user-destroy'],
        ];
        foreach ($permissionList as $item => $value){
            Permission::create($value);
        }
    }
}
