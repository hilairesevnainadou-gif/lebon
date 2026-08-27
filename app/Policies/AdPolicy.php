<?php

namespace App\Policies;

use App\Models\Ad;
use App\Models\User;

class AdPolicy
{
    // ── Un admin peut tout gérer, un vendeur seulement ses propres annonces ─

    public function manage(User $user, Ad $ad): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $ad->seller !== null && $ad->seller->user_id === $user->id;
    }
}
