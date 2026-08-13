<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole     = Role::where('slug', 'admin')->first();
        $vendeurRole   = Role::where('slug', 'vendeur')->first();
        $vendeurPcRole = Role::where('slug', 'vendeur-pc')->first();

        $adsPermissionId = Permission::where('slug', 'menu.ads.view')->value('id');
        $pcPermissionId  = Permission::where('slug', 'menu.pc.view')->value('id');

        $admin = User::updateOrCreate(
            ['email' => 'admin@lebon.fr'],
            [
                'name'              => 'Administrateur',
                'password'          => Hash::make('Admin@2024!'),
                'is_admin'          => true,
                'role_id'           => $adminRole?->id,
                'email_verified_at' => now(),
            ]
        );
        $admin->permissions()->sync(array_filter([$adsPermissionId, $pcPermissionId]));

        $vendeurs = [
            ['email' => 'vendeur@lebon.fr',  'name' => 'Vendeur Demo'],
            ['email' => 'vendeur2@lebon.fr', 'name' => 'Camille Martin'],
            ['email' => 'vendeur3@lebon.fr', 'name' => 'Sofiane Belkacem'],
        ];

        foreach ($vendeurs as $vendeur) {
            $user = User::updateOrCreate(
                ['email' => $vendeur['email']],
                [
                    'name'              => $vendeur['name'],
                    'password'          => Hash::make('Vendeur@2024!'),
                    'is_admin'          => false,
                    'role_id'           => $vendeurRole?->id,
                    'email_verified_at' => now(),
                ]
            );
            $user->permissions()->sync(array_filter([$adsPermissionId]));
        }

        // Vendeur avec accès à l'espace PC (rôle "Vendeur PC")
        $vendeurPc = User::updateOrCreate(
            ['email' => 'vendeurpc@lebon.fr'],
            [
                'name'              => 'Nova Tech',
                'password'          => Hash::make('VendeurPC@2024!'),
                'is_admin'          => false,
                'role_id'           => $vendeurPcRole?->id,
                'email_verified_at' => now(),
            ]
        );
        $vendeurPc->permissions()->sync(array_filter([$adsPermissionId, $pcPermissionId]));
    }
}
