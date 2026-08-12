<?php

namespace Database\Seeders;

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
        $adminRole   = Role::where('slug', 'admin')->first();
        $vendeurRole = Role::where('slug', 'vendeur')->first();

        User::updateOrCreate(
            ['email' => 'admin@lebon.fr'],
            [
                'name'              => 'Administrateur',
                'password'          => Hash::make('Admin@2024!'),
                'is_admin'          => true,
                'role_id'           => $adminRole?->id,
                'email_verified_at' => now(),
            ]
        );

        $vendeurs = [
            ['email' => 'vendeur@lebon.fr',  'name' => 'Vendeur Demo'],
            ['email' => 'vendeur2@lebon.fr', 'name' => 'Camille Martin'],
            ['email' => 'vendeur3@lebon.fr', 'name' => 'Sofiane Belkacem'],
        ];

        foreach ($vendeurs as $vendeur) {
            User::updateOrCreate(
                ['email' => $vendeur['email']],
                [
                    'name'              => $vendeur['name'],
                    'password'          => Hash::make('Vendeur@2024!'),
                    'is_admin'          => false,
                    'role_id'           => $vendeurRole?->id,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
