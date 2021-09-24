<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        $permissions = [
            [
                'group_name' => 'Post',
                'permissions' => [
                    'post.view',
                    'post.create',
                    'post.edit',
                    'post.update',
                    'post.delete',
                ],
                'formal_names' => [
                    'View post',
                    'Create post',
                    'Edit post',
                    'Update post',
                    'Delete post',
                ]
            ],
            [
                'group_name' => 'Category',
                'permissions' => [
                    'category.view',
                    'category.create',
                    'category.edit',
                    'category.update',
                    'category.delete',
                ],
                'formal_names' => [
                    'View category',
                    'Create category',
                    'Edit category',
                    'Update category',
                    'Delete category',
                ]
            ],
            [
                'group_name' => 'Page',
                'permissions' => [
                    'page.view',
                    'page.create',
                    'page.edit',
                    'page.update',
                    'page.delete',
                ],
                'formal_names' => [
                    'View page',
                    'Create page',
                    'Edit page',
                    'Update page',
                    'Delete page',
                ]
            ],
            [
                'group_name' => 'Role',
                'permissions' => [
                    'role.view',
                    'role.create',
                    'role.edit',
                    'role.update',
                    'role.delete',
                ],
                'formal_names' => [
                    'View roles',
                    'Create role',
                    'Edit role',
                    'Update role',
                    'Delete role',
                ]
            ],
            [
                'group_name' => 'Admin',
                'permissions' => [
                    'admin.view',
                    'admin.create',
                    'admin.edit',
                    'admin.update',
                    'admin.delete',
                ],
                'formal_names' => [
                    'View admin',
                    'Create admin',
                    'Edit admin',
                    'Update admin',
                    'Delete admin',
                ]
            ],
            [
                'group_name' => 'Super Admin',
                'permissions' => [
                    'super_admin.view',
                    'super_admin.create',
                    'super_admin.edit',
                    'super_admin.update',
                    'super_admin.delete',
                ],
                'formal_names' => [
                    'View super admin',
                    'Create super admin',
                    'Edit super admin',
                    'Update super admin',
                    'Delete super admin',
                ]
            ],
        ];

        $role = Role::create(['name' => 'Author']);
        foreach($permissions as $value)
        {
            $group_name = $value['group_name'];
            foreach($value['permissions'] as $index => $permission)
            {
                $perm = Permission::create([
                    'group_name' => $group_name,
                    'name' => $permission,
                    'formal_name' => $value['formal_names'][$index]
                ]);
                $role->givePermissionTo($perm);
            }
        }
        $role = Role::create(['name' => 'Post Author']);
        $value = $permissions[0];
        $group_name = $value['group_name'];
        foreach($value['permissions'] as $index => $permission)
        {
            $role->givePermissionTo($permission);
        }
    }
}
