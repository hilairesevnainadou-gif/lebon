<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            'vendeur@lebon.fr'   => ['pseudo' => 'Marc_Auto25',    'phone' => '06 12 34 56 78', 'city' => 'Besançon'],
            'vendeur2@lebon.fr'  => ['pseudo' => 'Camille_Cars',   'phone' => '06 98 76 54 32', 'city' => 'Lyon'],
            'vendeur3@lebon.fr'  => ['pseudo' => 'SofianeMotors',  'phone' => '07 45 12 89 63', 'city' => 'Marseille'],
            'vendeurpc@lebon.fr' => ['pseudo' => 'TechDeals_Nova', 'phone' => '06 55 44 33 22', 'city' => 'Toulouse'],
        ];

        foreach ($sellers as $email => $data) {
            $user = User::where('email', $email)->first();

            if (!$user) {
                continue;
            }

            Seller::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pseudo'         => $data['pseudo'],
                    'email'          => $user->email,
                    'phone'          => $data['phone'],
                    'city'           => $data['city'],
                    'is_reactive'    => true,
                    'last_active_at' => now(),
                ]
            );
        }
    }
}
