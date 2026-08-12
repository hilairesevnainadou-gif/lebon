<?php

namespace Database\Seeders;

use App\Models\Seller;
use App\Models\SellerBankAccount;
use Illuminate\Database\Seeder;

class SellerBankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Seller::all()->each(function (Seller $seller) {
            SellerBankAccount::updateOrCreate(
                ['seller_id' => $seller->id, 'is_default' => true],
                [
                    'iban'                 => fake()->iban('FR'),
                    'bic'                  => fake()->swiftBicNumber(),
                    'bank_name'            => fake()->randomElement(['Crédit Agricole', 'BNP Paribas', 'Société Générale', 'Banque Populaire']),
                    'account_holder_name'  => fake()->name(),
                ]
            );
        });
    }
}
