<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //membuat user
        User::create([
    		'name' => 'Admin Adinda',
    		'email' => 'admin@adinda.com',
    		'username' => 'adminadinda',
    		'password' => Hash::make('adindaadinda'),
    		// 'roles' => $request->roles,
    	]);
        $user = User::where('email','admin@adinda.com')->first();

        // membuat permission di awal dan menambahkan permission ke user
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $superadmin = Role::create(['name' => 'SuperAdmin']);
        $admin = Role::create(['name' => 'Admin']);
        $user->assignRole($superadmin);

        $permissions = [];
        $article_create = Permission::create(['name' => 'articles.create', 'guard_name' => 'web']);
        $article_read = Permission::create(['name' => 'articles.read', 'guard_name' => 'web']);
        $article_update = Permission::create(['name' => 'articles.update', 'guard_name' => 'web']);
        $article_delete = Permission::create(['name' => 'articles.delete', 'guard_name' => 'web']);
        $article_publish = Permission::create(['name' => 'articles.publish', 'guard_name' => 'web']);
        $article_unpublish = Permission::create(['name' => 'articles.unpublish', 'guard_name' => 'web']);
        $article_restore = Permission::create(['name' => 'articles.restore', 'guard_name' => 'web']);
        $article_verification = Permission::create(['name' => 'articles.verification', 'guard_name' => 'web']);

        $users_create = Permission::create(['name' => 'users.create', 'guard_name' => 'web']);
        $user_read = Permission::create(['name' => 'users.read', 'guard_name' => 'web']);
        $user_update = Permission::create(['name' => 'users.update', 'guard_name' => 'web']);
        $user_delete = Permission::create(['name' => 'users.delete', 'guard_name' => 'web']);
        $user_ban = Permission::create(['name' => 'users.ban', 'guard_name' => 'web']);
        $user_unban = Permission::create(['name' => 'users.unban', 'guard_name' => 'web']);
        $user_restore = Permission::create(['name' => 'users.restore', 'guard_name' => 'web']);
        $user_verification = Permission::create(['name' => 'users.verification', 'guard_name' => 'web']);

        $inovation_create = Permission::create(['name' => 'inovations.create', 'guard_name' => 'web']);
        $inovation_read = Permission::create(['name' => 'inovations.read', 'guard_name' => 'web']);
        $inovation_update = Permission::create(['name' => 'inovations.update', 'guard_name' => 'web']);
        $inovation_delete = Permission::create(['name' => 'inovations.delete', 'guard_name' => 'web']);
        $inovation_publish = Permission::create(['name' => 'inovations.publish', 'guard_name' => 'web']);
        $inovation_unpublish = Permission::create(['name' => 'inovations.unpublish', 'guard_name' => 'web']);
        $inovation_restore = Permission::create(['name' => 'inovations.restore', 'guard_name' => 'web']);
        $inovation_verification = Permission::create(['name' => 'inovations.verification', 'guard_name' => 'web']);

        $permissions = ['inovations.create', 'inovations.read', 'inovations.update', 'inovations.delete', 'inovations.publish', 'inovations.unpublish', 'inovations.restore', 'inovations.verification', 'articles.create', 'articles.read', 'articles.update', 'articles.delete', 'articles.publish', 'articles.unpublish', 'articles.restore', 'articles.verification', 'users.create', 'users.update', 'users.read', 'users.delete', 'users.ban', 'users.unban', 'users.restore', 'users.verification'];
        $superadmin->syncPermissions($permissions);
    }
}
