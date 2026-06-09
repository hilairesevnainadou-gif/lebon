<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lebon.fr'],
            [
                'name'               => 'Administrateur',
                'password'           => Hash::make('Admin@2024!'),
                'is_admin'           => true,
                'email_verified_at'  => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'vendeur@lebon.fr'],
            [
                'name'              => 'Vendeur Demo',
                'password'          => Hash::make('Vendeur@2024!'),
                'is_admin'          => false,
                'email_verified_at' => now(),
            ]
        );
    }
}
