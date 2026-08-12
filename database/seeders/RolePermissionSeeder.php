<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Voir mes annonces', 'slug' => 'menu.ads.view'],
            ['name' => 'Gérer les utilisateurs', 'slug' => 'menu.users.view'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['slug' => $permission['slug']], $permission);
        }

        $admin = Role::updateOrCreate(['slug' => 'admin'], ['name' => 'Administrateur']);
        $admin->permissions()->sync(Permission::pluck('id'));

        $vendeur = Role::updateOrCreate(['slug' => 'vendeur'], ['name' => 'Vendeur']);
        $vendeur->permissions()->sync(
            Permission::where('slug', 'menu.ads.view')->pluck('id')
        );
    }
}
