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
        // Note : l'accès à la gestion des utilisateurs n'est pas une permission
        // ici — il est contrôlé uniquement par is_admin (middleware "admin"),
        // voir UserController et AdminMiddleware.
        $permissions = [
            ['name' => 'Voir mes annonces', 'slug' => 'menu.ads.view'],
            ['name' => 'Voir l\'espace annonces PC', 'slug' => 'menu.pc.view'],
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

        $vendeurPc = Role::updateOrCreate(['slug' => 'vendeur-pc'], ['name' => 'Vendeur PC']);
        $vendeurPc->permissions()->sync(
            Permission::whereIn('slug', ['menu.ads.view', 'menu.pc.view'])->pluck('id')
        );
    }
}
