<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use FontLib\Table\Type\name;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'User Management',
                'description' => 'Can create, edit, view details, and delete user data.',
                'roles' => ['SuperAdmin']
            ],
            [
                'name' => 'Role Management',
                'description' => 'Can create, edit, view details and delete role data.',
                'roles' => ['SuperAdmin']
            ],
            [
                'name' => 'Permission Management',
                'description' => 'Can create, edit, view details, and delete access rights.',
                'roles' => ['SuperAdmin']
            ],
            [
                'name' => 'Category',
                'description' => 'Can create, edit, view details, and delete categories.',
                'roles' => ['SuperAdmin', 'Admin']
            ],
            [
                'name' => 'Division',
                'description' => 'Can create, edit, view details, and delete divisions.',
                'roles' => ['SuperAdmin', 'Admin']
            ],
            [
                'name' => 'Opportunity',
                'description' => 'Can create, edit, view details, and delete opportunities data.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff']
            ],
            [
                'name' => 'Applicant',
                'description' => 'Can view details and delete applicant data.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff']
            ],
            [
                'name' => 'CV Screening',
                'description' => 'Can edit and view CV screening results.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff']
            ],
            [
                'name' => 'Psikotest',
                'description' => 'Can edit and view psychotest results.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff'],
            ],
            [
                'name' => 'Interview HRD',
                'description' => 'Can edit and view HRD interview results.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff']
            ],
            [
                'name' => 'Interview User',
                'description' => 'Can edit and view user interview results.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff'],
            ],
            [
                'name' => 'Offering',
                'description' => 'Can edit and view offering data.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff'],
            ],
            [
                'name' => 'Recruitment Report',
                'description' => 'Can view and download applicant data reports.',
                'roles' => ['SuperAdmin', 'Admin', 'Staff'],
            ],
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::create([
                'name' => $perm['name'],
                'description' => $perm['description']
            ]);

            $roleIds = Role::whereIn('name', $perm['roles'])->pluck('id');
            $permission->roles()->attach($roleIds);
        }
    }
}
