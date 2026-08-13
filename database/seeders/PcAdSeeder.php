<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\AdFeature;
use App\Models\AdPhoto;
use App\Models\Computer;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PcAdSeeder extends Seeder
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $catalogue = [
        ['brand' => 'Dell', 'model' => 'XPS 15', 'cpu' => 'Intel Core i7-1260P', 'ram_gb' => 16, 'storage_type' => 'ssd', 'storage_gb' => 512, 'gpu' => 'NVIDIA RTX 4050', 'screen_size' => 15.6, 'os' => 'Windows 11', 'price' => 1290, 'rgb' => [40, 40, 45]],
        ['brand' => 'Apple', 'model' => 'MacBook Pro 14"', 'cpu' => 'Apple M3 Pro', 'ram_gb' => 18, 'storage_type' => 'ssd', 'storage_gb' => 512, 'gpu' => 'GPU intégré 14 cœurs', 'screen_size' => 14, 'os' => 'macOS', 'price' => 2190, 'rgb' => [150, 150, 155]],
        ['brand' => 'HP', 'model' => 'Pavilion Desktop', 'cpu' => 'AMD Ryzen 5 5600G', 'ram_gb' => 16, 'storage_type' => 'ssd', 'storage_gb' => 1000, 'gpu' => 'AMD Radeon Graphics', 'screen_size' => null, 'os' => 'Windows 11', 'price' => 590, 'rgb' => [60, 90, 130]],
        ['brand' => 'Lenovo', 'model' => 'ThinkPad X1 Carbon', 'cpu' => 'Intel Core i5-1335U', 'ram_gb' => 16, 'storage_type' => 'ssd', 'storage_gb' => 512, 'gpu' => 'Intel Iris Xe', 'screen_size' => 14, 'os' => 'Windows 11', 'price' => 1090, 'rgb' => [20, 20, 25]],
        ['brand' => 'Asus', 'model' => 'ROG Strix G16', 'cpu' => 'Intel Core i9-13980HX', 'ram_gb' => 32, 'storage_type' => 'ssd', 'storage_gb' => 1000, 'gpu' => 'NVIDIA RTX 4070', 'screen_size' => 16, 'os' => 'Windows 11', 'price' => 1990, 'rgb' => [130, 30, 40]],
    ];

    /**
     * @var array<int, string>
     */
    private array $featuresPool = [
        'Wifi', 'Bluetooth', 'Écran tactile', 'Rétroéclairage clavier',
        'Lecteur d\'empreintes', 'Webcam', 'Port USB-C', 'Garantie constructeur',
    ];

    public function run(): void
    {
        $seller = Seller::where('pseudo', 'TechDeals_Nova')->first();

        if (!$seller) {
            return;
        }

        foreach ($this->catalogue as $pc) {
            $ad = Ad::create([
                'seller_id'    => $seller->id,
                'category'     => 'pc',
                'title'        => "{$pc['brand']} {$pc['model']} - {$pc['ram_gb']} Go RAM",
                'description'  => "{$pc['brand']} {$pc['model']} en excellent état, {$pc['cpu']}, {$pc['ram_gb']} Go de RAM, {$pc['storage_gb']} Go de stockage {$pc['storage_type']}.",
                'price'        => $pc['price'],
                'city'         => $seller->city,
                'postal_code'  => null,
                'status'       => 'active',
                'published_at' => now()->subDays(rand(0, 15)),
                'share_token'  => Str::random(10),
                'views'        => rand(5, 300),
            ]);

            Computer::create([
                'ad_id'        => $ad->id,
                'brand'        => $pc['brand'],
                'model'        => $pc['model'],
                'cpu'          => $pc['cpu'],
                'ram_gb'       => $pc['ram_gb'],
                'storage_type' => $pc['storage_type'],
                'storage_gb'   => $pc['storage_gb'],
                'gpu'          => $pc['gpu'],
                'screen_size'  => $pc['screen_size'],
                'os'           => $pc['os'],
                'condition'    => 'Très bon état',
            ]);

            AdFeature::syncForAd($ad->id, Arr::random($this->featuresPool, rand(3, 5)));

            foreach (range(0, rand(1, 3)) as $i) {
                $data     = $this->generatePlaceholderImage("{$pc['brand']} {$pc['model']}", $pc['rgb']);
                $filename = "ads/{$ad->id}/photos/seed-" . Str::random(8) . '.jpg';

                Storage::disk('public')->put($filename, $data);

                AdPhoto::create([
                    'ad_id'         => $ad->id,
                    'disk'          => 'public',
                    'path'          => $filename,
                    'original_name' => "{$pc['brand']}-{$pc['model']}-{$i}.jpg",
                    'mime_type'     => 'image/jpeg',
                    'size'          => strlen($data),
                    'order'         => $i,
                ]);
            }
        }
    }

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
