<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{

    public static $permissions = [
        'admin'=>[
            'admin-index',
            'admin-showUser',
            'task-index',
            'task-create',
            'task-update',
            'task-delete',
            'task-assign',
            'task-enable',
            'task-disable',
        ],
        'staff'=>[
            'staff-index',
        ],
        'common'=>[
            'task-show',
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (self::$permissions as $permissionsByRole) {
            foreach ($permissionsByRole as $permission) {
                Permission::create(['name'=>$permission]);
            }
        }
    }
}
