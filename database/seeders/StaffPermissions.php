<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $staffRole = Role::findByName('staff');


        // foreach (PermissionSeeder::$permissions as $type=>$permissionsByType) {
        //     if ($type === 'staff' or $type === 'common') {
        //         foreach ($permissionsByType as $permission) {
        //             $staffRole->givePermissionTo($permission);
        //         }
        //     }
        // }

        // $staffRole->syncPermissions(PermissionSeeder::$permissions['staff']);
        // $staffRole->syncPermissions(PermissionSeeder::$permissions['common']);
    }
}
