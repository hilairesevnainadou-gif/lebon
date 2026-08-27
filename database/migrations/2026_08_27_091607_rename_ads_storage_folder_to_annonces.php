<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Déplace les photos déjà publiées de "ads/{id}/photos" vers
     * "annonces/{id}/photos" : certains bloqueurs de publicités
     * (uBlock, AdBlock…) bloquent côté navigateur toute URL contenant
     * "/ads/" (ERR_BLOCKED_BY_CLIENT), même si le serveur répond bien.
     */
    public function up(): void
    {
        $disk = Storage::disk('public');

        $photos = DB::table('ad_photos')
            ->where('disk', 'public')
            ->where('path', 'like', 'ads/%')
            ->get();

        foreach ($photos as $photo) {
            $newPath = 'annonces/' . substr($photo->path, strlen('ads/'));

            if ($disk->exists($photo->path) && !$disk->exists($newPath)) {
                $disk->move($photo->path, $newPath);
            }

            DB::table('ad_photos')->where('id', $photo->id)->update(['path' => $newPath]);
        }
    }

    public function down(): void
    {
        $disk = Storage::disk('public');

        $photos = DB::table('ad_photos')
            ->where('disk', 'public')
            ->where('path', 'like', 'annonces/%')
            ->get();

        foreach ($photos as $photo) {
            $oldPath = 'ads/' . substr($photo->path, strlen('annonces/'));

            if ($disk->exists($photo->path) && !$disk->exists($oldPath)) {
                $disk->move($photo->path, $oldPath);
            }

            DB::table('ad_photos')->where('id', $photo->id)->update(['path' => $oldPath]);
        }
    }
};
