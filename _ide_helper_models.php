<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdFeature> $features
 * @property-read int|null $features_count
 * @property-read string $formatted_price
 * @property-read \App\Models\AdPhoto|null $main_photo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdPhoto> $photos
 * @property-read int|null $photos_count
 * @property-read \App\Models\Seller|null $seller
 * @property-read \App\Models\Vehicle|null $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad byCity(string $city)
 * @method static \Database\Factories\AdFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad priceBetween(float $min, float $max)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ad sold()
 */
	class Ad extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Ad|null $ad
 * @method static \Database\Factories\AdFeatureFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdFeature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdFeature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdFeature query()
 */
	class AdFeature extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \App\Models\Ad|null $ad
 * @property-read string $formatted_size
 * @property-read string $url
 * @method static \Database\Factories\AdPhotoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdPhoto query()
 */
	class AdPhoto extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ad> $ads
 * @property-read int|null $ads_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerBankAccount> $bankAccounts
 * @property-read int|null $bank_accounts_count
 * @property-read \App\Models\SellerBankAccount|null $defaultBankAccount
 * @property-read string $full_name
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\SellerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Seller query()
 */
	class Seller extends \Eloquent {}
}

namespace App\Models{
/**
 * @property-read string $masked_iban
 * @property-read \App\Models\Seller|null $seller
 * @method static \Database\Factories\SellerBankAccountFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerBankAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerBankAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellerBankAccount query()
 */
	class SellerBankAccount extends \Eloquent {}
}

