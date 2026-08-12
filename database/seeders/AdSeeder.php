<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdFeature;
use App\Models\AdPhoto;
use App\Models\Seller;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdSeeder extends Seeder
{
    /**
     * Catalogue de véhicules d'exemple utilisé pour générer des annonces réalistes.
     *
     * @var array<int, array<string, mixed>>
     */
    private array $catalogue = [
        ['brand' => 'Peugeot', 'model' => '308', 'year' => 2020, 'mileage' => 45000, 'fuel_type' => 'diesel', 'gearbox' => 'manuelle', 'body_type' => 'Berline', 'color' => 'Gris Artense', 'price' => 16900, 'rgb' => [90, 110, 140]],
        ['brand' => 'Renault', 'model' => 'Clio V', 'year' => 2021, 'mileage' => 28000, 'fuel_type' => 'essence', 'gearbox' => 'manuelle', 'body_type' => 'Citadine', 'color' => 'Bleu Iron', 'price' => 13500, 'rgb' => [50, 90, 160]],
        ['brand' => 'Volkswagen', 'model' => 'Golf VIII', 'year' => 2022, 'mileage' => 19000, 'fuel_type' => 'essence', 'gearbox' => 'automatique', 'body_type' => 'Compacte', 'color' => 'Blanc Pur', 'price' => 22900, 'rgb' => [220, 220, 225]],
        ['brand' => 'Citroën', 'model' => 'C3', 'year' => 2019, 'mileage' => 62000, 'fuel_type' => 'diesel', 'gearbox' => 'manuelle', 'body_type' => 'Citadine', 'color' => 'Rouge Aden', 'price' => 10900, 'rgb' => [170, 50, 45]],
        ['brand' => 'BMW', 'model' => 'Série 3', 'year' => 2021, 'mileage' => 35000, 'fuel_type' => 'diesel', 'gearbox' => 'automatique', 'body_type' => 'Berline', 'color' => 'Noir Saphir', 'price' => 32900, 'rgb' => [30, 30, 35]],
        ['brand' => 'Toyota', 'model' => 'Yaris', 'year' => 2022, 'mileage' => 15000, 'fuel_type' => 'hybride', 'gearbox' => 'automatique', 'body_type' => 'Citadine', 'color' => 'Gris Titane', 'price' => 18500, 'rgb' => [120, 125, 130]],
        ['brand' => 'Audi', 'model' => 'A3', 'year' => 2020, 'mileage' => 41000, 'fuel_type' => 'essence', 'gearbox' => 'automatique', 'body_type' => 'Compacte', 'color' => 'Bleu Ascari', 'price' => 24900, 'rgb' => [40, 70, 110]],
        ['brand' => 'Dacia', 'model' => 'Sandero', 'year' => 2023, 'mileage' => 8000, 'fuel_type' => 'essence', 'gearbox' => 'manuelle', 'body_type' => 'Citadine', 'color' => 'Orange Arizona', 'price' => 12900, 'rgb' => [200, 110, 40]],
    ];

    /**
     * @var array<int, string>
     */
    private array $featuresPool = [
        'Climatisation', 'GPS', 'Bluetooth', 'Régulateur de vitesse', 'Caméra de recul',
        'Toit ouvrant', 'Sièges chauffants', 'Jantes alliage', 'Radar de stationnement',
        'Vitres électriques', 'Aide au stationnement', 'Régulateur adaptatif',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = Seller::all();

        if ($sellers->isEmpty()) {
            return;
        }

        $vehicleIndex = 0;

        foreach ($sellers as $seller) {
            $adsCount = rand(2, 3);

            for ($i = 0; $i < $adsCount; $i++) {
                $car = $this->catalogue[$vehicleIndex % count($this->catalogue)];
                $vehicleIndex++;

                $ad = Ad::create([
                    'seller_id'    => $seller->id,
                    'title'        => "{$car['brand']} {$car['model']} {$car['year']} - Très bon état",
                    'description'  => "Magnifique {$car['brand']} {$car['model']} de {$car['year']}, entretien suivi, carnet à jour. Non fumeur, véhicule sans accident.",
                    'price'        => $car['price'],
                    'city'         => $seller->city,
                    'postal_code'  => null,
                    'likes_count'  => rand(0, 40),
                    'status'       => 'active',
                    'published_at' => now()->subDays(rand(0, 20)),
                    'share_token'  => Str::random(10),
                    'views'        => rand(10, 500),
                ]);

                Vehicle::create([
                    'ad_id'                    => $ad->id,
                    'brand'                    => $car['brand'],
                    'model'                    => $car['model'],
                    'year'                     => $car['year'],
                    'mileage'                  => $car['mileage'],
                    'fuel_type'                => $car['fuel_type'],
                    'gearbox'                  => $car['gearbox'],
                    'doors'                    => 5,
                    'seats'                    => 5,
                    'body_type'                => $car['body_type'],
                    'color'                    => $car['color'],
                    'condition'                => 'Bon état',
                    'first_registration_date'  => "{$car['year']}-01-15",
                ]);

                AdFeature::syncForAd($ad->id, Arr::random($this->featuresPool, rand(3, 6)));

                $this->createPhotos($ad, $car);
            }
        }
    }

    /**
     * Génère et enregistre 2 à 4 photos placeholder pour une annonce.
     */
    private function createPhotos(Ad $ad, array $car): void
    {
        $count = rand(2, 4);

        for ($i = 0; $i < $count; $i++) {
            $data     = $this->generatePlaceholderImage("{$car['brand']} {$car['model']}", $car['rgb']);
            $filename = "ads/{$ad->id}/photos/seed-" . Str::random(8) . '.jpg';

            Storage::disk('public')->put($filename, $data);

            AdPhoto::create([
                'ad_id'         => $ad->id,
                'disk'          => 'public',
                'path'          => $filename,
                'original_name' => "{$car['brand']}-{$car['model']}-{$i}.jpg",
                'mime_type'     => 'image/jpeg',
                'size'          => strlen($data),
                'order'         => $i,
            ]);
        }
    }

    /**
     * Génère une image JPEG de remplacement (fond coloré + libellé du véhicule).
     */
    private function generatePlaceholderImage(string $label, array $rgb): string
    {
        $width  = 800;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);
        $bg    = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefill($image, 0, 0, $bg);

        $white = imagecolorallocate($image, 255, 255, 255);
        $font  = 5;
        $textWidth = imagefontwidth($font) * strlen($label);
        $x = (int) (($width - $textWidth) / 2);
        $y = (int) ($height / 2);
        imagestring($image, $font, $x, $y, $label, $white);

        ob_start();
        imagejpeg($image, null, 85);
        $data = ob_get_clean();
        imagedestroy($image);

        return $data;
    }
}
