<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::where('name', 'super-admin')->get()->first();

        if (!isset($role)) {
            $role = Role::create(['name' => 'super-admin']);
        }

        $user = User::where('name', 'super-admin')->where('email', 'super-admin@admin.com')->get()->first();

        if (!isset($user)) {
            $user = Factory(App\User::class)->create([
                'name' => 'super-admin',
                'email' => 'super-admin@admin.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole($role);
        }

        $permissions = [
            // Access Permissions
            'access cms',
            'access downloads',
            'access export import',
            'access manage users',
            'access media',
            'access modules',
            'access orders',
            'access quotations',
            'access questions',
            'access settings',
            'access store',
            'access dashboard',
            // Create Permissions
            'create category',
            'create order',
            'create quotation',
            'create question',
            'create permission',
            'create personalisation type',
            'create primary color',
            'create product',
            'create role',
            'create user',
            'create setting',
            'create page',
            'create post',
            'create usb type',
            'create personalisation option',
            'create manufacturer',
            'create quantity',
            'create client',
            // Delete Permissions
            'delete category',
            'delete order',
            'delete quotation',
            'delete question',
            'delete permission',
            'delete personalisation type',
            'delete primary color',
            'delete product',
            'delete role',
            'delete user',
            'delete page',
            'delete post',
            'delete usb type',
            'delete personalisation option',
            'delete quantity',
            'delete manufacturer',
            'delete cache',
            'delete client',
            // Edit Permissions
            'edit category',
            'edit order',
            'edit quotation',
            'edit question',
            'edit permission',
            'edit personalisation type',
            'edit primary color',
            'edit product',
            'edit role',
            'edit user',
            'edit setting',
            'edit page',
            'edit post',
            'edit usb type',
            'edit personalisation option',
            'edit quantity',
            'edit manufacturer',
            'edit client',
            // View Permissions
            'view categories',
            'view orders',
            'view quotations',
            'view questions',
            'view permissions',
            'view personalisation types',
            'view primary colors',
            'view products',
            'view roles',
            'view users',
            'view printing agencies',
            'view settings',
            'view pages',
            'view posts',
            'view usb types',
            'view personalisation options',
            'view manufacturers',
            'view quantities',
            'view client',
            // Import Permissions
            'import category markups',
            'import categories',
            'import products',
            // Export Permissions
            'export personalisation type markups',
            'export personalisation prices',
            'export category markups',
            'export categories',
            'export products',
            // Upload Permissions
            'upload categories',
            'upload category markups',
            'upload products'
        ];

        $permissions_name_array = Permission::pluck('name')->toArray();

        foreach ($permissions as $permission) {
            if (!in_array($permission, $permissions_name_array)) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}
