<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use App\Models\AdDraft;
use App\Models\AdFeature;
use App\Models\AdLike;
use App\Models\AdPhoto;
use App\Models\Reservation;
use App\Models\Seller;
use App\Models\SellerBankAccount;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdController extends Controller
{
    // ── Autorisation : accès à l'espace annonces véhicule ────────

    private function authorizeAdsAccess(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermission('menu.ads.view'), 403);
    }

    // ── Autorisation : vendeur ne peut agir que sur ses annonces ─

    private function authorizeAd(Ad $ad): void
    {
        $this->authorizeAdsAccess();
        abort_unless(Auth::user()->can('manage', $ad), 403);
    }

    // ── Liste des annonces (publiées + brouillons) ────────────

    public function index(): View
    {
        $this->authorizeAdsAccess();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Récupérer les annonces publiées (admin = tout, sinon seulement les siennes)
        $publishedAds = $user->isAdmin()
            ? Ad::with(['vehicle', 'photos'])->category('vehicule')->latest()->get()
            : Ad::with(['vehicle', 'photos'])
                ->category('vehicule')
                ->whereHas('seller', fn($q) => $q->where('user_id', $user->id))
                ->latest()
                ->get();

        // Récupérer les brouillons (admin voit tous les brouillons)
        $drafts = $user->isAdmin()
            ? AdDraft::orderBy('updated_at', 'desc')->get()
            : AdDraft::where('user_id', $user->id)->orderBy('updated_at', 'desc')->get();

        // Fusionner les deux collections
        $allAds = $publishedAds->concat($drafts)->sortByDesc('updated_at');

        // Paginer manuellement
        $currentPage = request()->query('page', 1);
        $perPage = 12;
        $currentItems = $allAds->slice(($currentPage - 1) * $perPage, $perPage);
        $ads = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allAds->count(),
            $perPage,
            $currentPage,
            ['path' => route('ads.index')]
        );

        return view('ads.index', compact('ads'));
    }

    // ── Vue admin : toutes les annonces, toutes catégories, tous statuts ──

    public function adminAll(Request $request): View
    {
        $category = $request->query('category');
        $status   = $request->query('status');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Si admin, retirer les global scopes pour voir toutes les annonces
        $base = $user && $user->isAdmin() ? Ad::withoutGlobalScopes() : Ad::query();
        $query = $base->with(['vehicle', 'computer', 'photos', 'seller'])->latest();

        if (in_array($category, ['vehicule', 'pc'], true)) {
            $query->category($category);
        }

        if (in_array($status, ['active', 'paused', 'sold'], true)) {
            $query->where('status', $status);
        }

        $ads = $query->paginate(20)->withQueryString();

        $counts = [
            'total'    => Ad::count(),
            'active'   => Ad::where('status', 'active')->count(),
            'paused'   => Ad::where('status', 'paused')->count(),
            'sold'     => Ad::where('status', 'sold')->count(),
            'vehicule' => Ad::category('vehicule')->count(),
            'pc'       => Ad::category('pc')->count(),
        ];

        return view('ads.admin-index', compact('ads', 'counts', 'category', 'status'));
    }

    // ── Formulaire de création ────────────────────────────────

    public function create(Request $request): View
    {
        $this->authorizeAdsAccess();

        $draftData = null;

        // Vérifier si on reprend un brouillon
        if ($request->has('draft_id')) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $draft = AdDraft::where('id', $request->draft_id)
                ->where('user_id', $user->id)
                ->first();

            if ($draft) {
                $draftData = array_merge($draft->data, ['id' => $draft->id]);
            }
        } elseif (session('draft')) {
            $draftData = session('draft');
        }

        return view('ads.create', compact('draftData'));
    }

    // ── Publication de l'annonce ──────────────────────────────

    public function store(StoreAdRequest $request): RedirectResponse
{
    $this->authorizeAdsAccess();

    DB::beginTransaction();

    /** @var Ad|null $ad */
    $ad = null;

    try {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ─────────────────────────────────────────────
        // 1. Récupérer les données du vendeur
        // ─────────────────────────────────────────────

        $sellerData = $request->input('seller', []);

        $seller = Seller::updateOrCreate(
            [
                'user_id' => $user->id,
                'email'   => $sellerData['email'],
            ],
            [
                'pseudo'        => $sellerData['pseudo'] ?? null,
                'first_name'    => $sellerData['first_name'] ?? null,
                'last_name'     => $sellerData['last_name'] ?? null,
                'phone'         => $sellerData['phone'] ?? null,
                'city'          => $sellerData['city'] ?? null,
                'is_reactive'   => true,
                'last_active_at'=> now(),
            ]
        );

        // ─────────────────────────────────────────────
        // 2. Données de l'annonce
        // ─────────────────────────────────────────────

        $adData = $request->input('ad', []);

        // ─────────────────────────────────────────────
        // 3. Création de l'annonce
        // ─────────────────────────────────────────────

        $ad = Ad::create([
            'seller_id'    => $seller->id,
            'title'        => $adData['title'],
            'description'  => $adData['description'] ?? null,
            'price'        => $adData['price'],
            'city'         => $adData['city'],
            'postal_code'  => $adData['postal_code'] ?? null,
            'likes_count'  => (int) ($adData['likes_count'] ?? 0),
            'status'       => 'active',
            'published_at' => now(),
        ]);

        // ─────────────────────────────────────────────
        // 4. COMPTE BANCAIRE PROPRE À L'ANNONCE
        // ─────────────────────────────────────────────

        $bankData = $request->input('bank', []);

        $cleanedIban = preg_replace(
            '/\s+/',
            '',
            (string) ($bankData['iban'] ?? '')
        );

        $ad->bankAccount()->create([
            'seller_id'           => $seller->id,
            'ad_id'               => $ad->id,
            'iban'                => $cleanedIban,
            'bic'                 => strtoupper(
                (string) ($bankData['bic'] ?? '')
            ),
            'bank_name'           => $bankData['bank_name'] ?? null,
            'account_holder_name' => $bankData['account_holder_name'] ?? null,
            'is_default'          => true,
        ]);

        // ─────────────────────────────────────────────
        // 5. Véhicule
        // ─────────────────────────────────────────────

        $vehicleData = $request->input('vehicle', []);

        Vehicle::create(
            array_merge(
                $vehicleData,
                [
                    'ad_id' => $ad->id,
                ]
            )
        );

        // ─────────────────────────────────────────────
        // 6. Équipements
        // ─────────────────────────────────────────────

        if ($request->filled('features')) {
            $features = $request->input('features');

            AdFeature::syncForAd(
                $ad->id,
                $features
            );
        }

        // ─────────────────────────────────────────────
        // 7. Photos
        // ─────────────────────────────────────────────

        if ($request->hasFile('photos')) {

            foreach ($request->file('photos') as $index => $file) {

                $filename = Str::uuid()
                    . '.'
                    . $file->getClientOriginalExtension();

                $directory = 'ads/' . $ad->id . '/photos';

                $path = $file->storeAs(
                    $directory,
                    $filename,
                    'public'
                );

                AdPhoto::create([
                    'ad_id'         => $ad->id,
                    'disk'          => 'public',
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'order'         => $index,
                ]);
            }
        }

        // ─────────────────────────────────────────────
        // 8. Supprimer le brouillon
        // ─────────────────────────────────────────────

        if ($request->has('draft_id')) {

            $draftToClear = AdDraft::where('id', $request->draft_id)
                ->where('user_id', $user->id)
                ->first();

            if ($draftToClear) {
                foreach ($draftToClear->data['draft_photos'] ?? [] as $draftPhotoPath) {
                    Storage::disk('public')->delete($draftPhotoPath);
                }
                $draftToClear->delete();
            }
        }

        // ─────────────────────────────────────────────
        // 9. Validation transactionnelle
        // ─────────────────────────────────────────────

        DB::commit();

        return redirect()
            ->route('ads.show', $ad)
            ->with(
                'success',
                'Votre annonce et son compte bancaire ont été publiés avec succès !'
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        if ($ad instanceof Ad) {

            AdPhoto::deleteAllForAd($ad->id);

            // Supprimer le compte bancaire associé
            SellerBankAccount::where('ad_id', $ad->id)->delete();

            $ad->delete();
        }

        return back()
            ->withInput()
            ->with(
                'error',
                'Une erreur est survenue : ' . $e->getMessage()
            );
    }
}

    // ── Formulaire de modification ───────────────────────────

    public function edit(Ad $ad): View
    {
        $this->authorizeAd($ad);
        $ad->load([
    'vehicle',
    'photos',
    'features',
    'bankAccount',
]);
        return view('ads.edit', compact('ad'));
    }

    // ── Enregistrement des modifications ─────────────────────

   public function update(
    UpdateAdRequest $request,
    Ad $ad
): RedirectResponse {

    $this->authorizeAd($ad);

    DB::beginTransaction();

    try {

        $adData = $request->input('ad', []);

        $vehicleData = $request->input('vehicle', []);

        // ─────────────────────────────────────────────
        // 1. Mise à jour de l'annonce
        // ─────────────────────────────────────────────

        $ad->update([
            'title'       => $adData['title'],
            'description' => $adData['description'] ?? null,
            'price'       => $adData['price'],
            'city'        => $adData['city'],
            'postal_code' => $adData['postal_code'] ?? null,
            'likes_count' => (int) ($adData['likes_count'] ?? 0),
            'status'      => $adData['status'] ?? $ad->status,
        ]);

        // ─────────────────────────────────────────────
        // 2. Mise à jour du compte bancaire
        // ─────────────────────────────────────────────

        if ($request->filled('bank')) {

            $bankData = $request->input('bank', []);

            $cleanedIban = preg_replace(
                '/\s+/',
                '',
                (string) ($bankData['iban'] ?? '')
            );

            $bankAccount = $ad->bankAccount;

            if ($bankAccount) {

                $bankAccount->update([
                    'seller_id'           => $ad->seller_id,
                    'iban'                => $cleanedIban,
                    'bic'                 => strtoupper(
                        (string) ($bankData['bic'] ?? '')
                    ),
                    'bank_name'           => $bankData['bank_name'] ?? null,
                    'account_holder_name' => $bankData['account_holder_name'] ?? null,
                    'is_default'          => true,
                ]);

            } else {

                $ad->bankAccount()->create([
                    'seller_id'           => $ad->seller_id,
                    'ad_id'               => $ad->id,
                    'iban'                => $cleanedIban,
                    'bic'                 => strtoupper(
                        (string) ($bankData['bic'] ?? '')
                    ),
                    'bank_name'           => $bankData['bank_name'] ?? null,
                    'account_holder_name' => $bankData['account_holder_name'] ?? null,
                    'is_default'          => true,
                ]);
            }
        }

        // ─────────────────────────────────────────────
        // 3. Mise à jour du véhicule
        // ─────────────────────────────────────────────

        if ($ad->vehicle) {
            $ad->vehicle->update($vehicleData);
        }

        // ─────────────────────────────────────────────
        // 4. Équipements
        // ─────────────────────────────────────────────

        AdFeature::syncForAd(
            $ad->id,
            $request->input('features', [])
        );

        // ─────────────────────────────────────────────
        // 5. Ajout de photos
        // ─────────────────────────────────────────────

        if ($request->hasFile('photos')) {

            $existingCount = $ad->photos()->count();

            foreach (
                $request->file('photos')
                as $index => $file
            ) {

                if ($existingCount + $index >= 12) {
                    break;
                }

                $filename = Str::uuid()
                    . '.'
                    . $file->getClientOriginalExtension();

                $path = $file->storeAs(
                    'ads/' . $ad->id . '/photos',
                    $filename,
                    'public'
                );

                AdPhoto::create([
                    'ad_id'         => $ad->id,
                    'disk'          => 'public',
                    'path'          => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'order'         => $existingCount + $index,
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('ads.show', $ad)
            ->with(
                'success',
                'Annonce et compte bancaire mis à jour avec succès.'
            );

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                'Une erreur est survenue : ' . $e->getMessage()
            );
    }
}

    // ── Réorganisation des photos ────────────────────────────

    public function reorderPhotos(Ad $ad, Request $request): JsonResponse
    {
        $this->authorizeAd($ad);
        $ids = $request->input('order', []);
        foreach ($ids as $position => $photoId) {
            $ad->photos()->where('id', (int) $photoId)->update(['order' => $position]);
        }
        return response()->json(['success' => true]);
    }

    // ── Suppression d'une photo ──────────────────────────────

    public function destroyPhoto(Ad $ad, AdPhoto $photo): JsonResponse
    {
        $this->authorizeAd($ad);
        if ($photo->ad_id !== $ad->id) {
            abort(403);
        }
        $photo->deleteWithFile();
        return response()->json(['success' => true, 'remaining' => $ad->photos()->count()]);
    }

    // ── Vue privée d'une annonce ─────────────────────────────

    public function show(Ad $ad): View
    {
        $this->authorizeAd($ad);
        $ad->load([
    'seller',
    'vehicle',
    'photos',
    'features',
    'bankAccount',
]);

        $ad->incrementViews();

        $ip         = request()->ip();
        $isLiked    = $ad->isLikedByIp($ip);
        $likesCount = $ad->likes()->count() + ($ad->likes_count ?? 0);

        return view('ads.show', compact('ad', 'isLiked', 'likesCount'));
    }

    // ── Toggle like (public, sans authentification) ──────────

    public function toggleLike(Ad $ad, Request $request): JsonResponse
    {
        $ip      = $request->ip();
        $existing = $ad->likes()->where('ip_address', $ip)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            $ad->likes()->create(['ip_address' => $ip]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $ad->likes()->count() + ($ad->likes_count ?? 0),
        ]);
    }

    // ── Formulaire de réservation (public) ───────────────────

   public function reserve(Ad $ad): View
{
    $ad->load([
        'vehicle',
        'photos',
        'seller',
        'bankAccount',
    ]);

    $ip = request()->ip();

    $isLiked = $ad->isLikedByIp($ip);

    $likesTotal =
        $ad->likes()->count()
        + ($ad->likes_count ?? 0);

    return view(
        'ads.reserve',
        compact(
            'ad',
            'isLiked',
            'likesTotal'
        )
    );
}

    public function reserveForm(Ad $ad): View
    {
        $ad->load(['vehicle', 'photos', 'seller']);
        $plan     = request()->query('plan', 'sans_garantie');
        $duration = request()->query('duration', null);
        return view('ads.reserve-form', compact('ad', 'plan', 'duration'));
    }

    // ── Enregistrement de la réservation ─────────────────────

    private array $reservationPlans = [
        'sans_garantie' => ['label' => 'Sans Garantie Panne Mécanique', 'price' => 19.99],
        'garantie_3'    => ['label' => 'Avec Garantie — 3 mois',        'price' => 139],
        'garantie_6'    => ['label' => 'Avec Garantie — 6 mois',        'price' => 259],
        'garantie_12'   => ['label' => 'Avec Garantie — 12 mois',       'price' => 399],
    ];

    public function reserveRecap(Ad $ad): View
    {
        $ad->load(['vehicle', 'photos']);
        $planKey  = request()->query('plan', 'sans_garantie');
        $planInfo = $this->reservationPlans[$planKey] ?? $this->reservationPlans['sans_garantie'];

        return view('ads.reserve-confirm', compact('ad', 'planInfo', 'planKey'));
    }

   public function reserveVirement(Ad $ad): View
{
    $ad->load([
        'vehicle',
        'photos',
        'seller',
        'bankAccount',
    ]);

    $planKey = request()->query(
        'plan',
        'sans_garantie'
    );

    $planInfo = $this->reservationPlans[$planKey]
        ?? $this->reservationPlans['sans_garantie'];

    $total = (float) request()->query(
        'amount',
        ($ad->price ?? 0) + $planInfo['price']
    );

    $amount = $total - $planInfo['price'];

    // IMPORTANT :
    // Le compte bancaire appartient maintenant
    // directement à cette annonce.
    $bankAccount = $ad->bankAccount;

    return view(
        'ads.reserve-done',
        compact(
            'ad',
            'planInfo',
            'planKey',
            'amount',
            'total',
            'bankAccount'
        )
    );
}

    public function storeVirementDeclaration(Request $request, Ad $ad): \Illuminate\Http\JsonResponse
    {
        $plan      = $request->input('plan', 'sans_garantie');
        $amount    = (float) $request->input('amount', $ad->price ?? 0);
        $reference = $request->input('reference', '');

        Reservation::create([
            'ad_id'     => $ad->id,
            'plan'      => $plan,
            'amount'    => $amount,
            'reference' => $reference,
            'status'    => 'virement_declared',
            'token'     => Str::random(32),
        ]);

        return response()->json(['ok' => true]);
    }

    public function storeReservation(Request $request, Ad $ad): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255'],
            'phone'      => ['required', 'string', 'max:20'],
            'message'    => ['nullable', 'string', 'max:1000'],
            'plan'       => ['nullable', 'string', 'in:sans_garantie,garantie_3mois,garantie_6mois,garantie_12mois'],
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required'  => 'Le nom est obligatoire.',
            'email.required'      => "L'email est obligatoire.",
            'email.email'         => "L'adresse email n'est pas valide.",
            'phone.required'      => 'Le téléphone est obligatoire.',
        ]);

        $reservation = Reservation::create(array_merge($validated, [
            'ad_id'  => $ad->id,
            'status' => 'pending',
            'token'  => Str::random(32),
        ]));

        return redirect()->route('ads.reserve.confirmed', [$ad, $reservation]);
    }

    // ── Page de confirmation de réservation ──────────────────

    public function reservationConfirmed(Ad $ad, Reservation $reservation): View
    {
        abort_if($reservation->ad_id !== $ad->id, 404);
        $ad->load(['vehicle', 'photos']);
        return view('ads.reserve-done', compact('ad', 'reservation'));
    }

    // ── Activer / Désactiver une annonce ─────────────────────

    public function toggleStatus(Ad $ad): RedirectResponse
    {
        $this->authorizeAd($ad);
        $ad->status = $ad->status === 'active' ? 'paused' : 'active';
        $ad->save();

        $label = $ad->status === 'active' ? 'réactivée' : 'désactivée';

        return redirect()->route('ads.show', $ad)
            ->with('success', "L'annonce a été {$label} avec succès.");
    }

    // ── Générer le lien public ───────────────────────────────

    public function share(Ad $ad): RedirectResponse
    {
        $this->authorizeAd($ad);
        if (!$ad->share_token) {
            $ad->update(['share_token' => Str::random(10)]);
        }

        $publicUrl = route('ads.public', ['c' => $ad->share_token]);

        return redirect()->route('ads.show', $ad)
            ->with('share_url', $publicUrl);
    }

    // ── Vue publique : favoris par IP ────────────────────────

    public function favorites(Request $request): View
    {
        $ip = $request->ip();

        $likedAdIds = AdLike::where('ip_address', $ip)->pluck('ad_id');

        $ads = Ad::with(['photos', 'vehicle', 'seller'])
            ->whereIn('id', $likedAdIds)
            ->where('status', 'active')
            ->orderByDesc('published_at')
            ->get();

        return view('ads.favorites', compact('ads'));
    }

    // ── Vue publique via lien partagé ────────────────────────

    public function publicShow(Request $request): View
    {
        $token = $request->query('c');

        $ad = Ad::with([
    'seller',
    'vehicle',
    'photos',
    'features',
    'bankAccount',
])
    ->where('share_token', $token)
    ->where('status', 'active')
    ->firstOrFail();

        $ip         = $request->ip();
        $isLiked    = $ad->isLikedByIp($ip);
        $likesTotal = $ad->likes()->count() + ($ad->likes_count ?? 0);

        return view('ads.public', compact('ad', 'isLiked', 'likesTotal'));
    }

    // ── Sauvegarde du brouillon en base de données ───────────

    public function saveDraft(Request $request): JsonResponse
    {
        $this->authorizeAdsAccess();

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Récupérer les données texte uniquement (les fichiers ne sont pas sérialisables)
            $draftData = $request->except(['_token', 'draft_id', 'photos']);

            // Vérifier si un brouillon existe déjà
            $draft = AdDraft::where('user_id', $user->id)->first();

            // Gérer les photos uploadées
            if ($request->hasFile('photos')) {
                // Supprimer les anciennes photos du brouillon avant d'en sauvegarder de nouvelles
                if ($draft && !empty($draft->data['draft_photos'])) {
                    foreach ($draft->data['draft_photos'] as $oldPath) {
                        Storage::disk('public')->delete($oldPath);
                    }
                }

                $savedPaths = [];
                foreach ($request->file('photos') as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('drafts/' . $user->id, $filename, 'public');
                    $savedPaths[] = $path;
                }
                $draftData['draft_photos'] = $savedPaths;
            } elseif ($draft && !empty($draft->data['draft_photos'])) {
                // Conserver les photos déjà sauvegardées si aucune nouvelle photo n'est envoyée
                $draftData['draft_photos'] = $draft->data['draft_photos'];
            }

            if ($draft) {
                $draft->update(['data' => $draftData]);
            } else {
                AdDraft::create([
                    'user_id' => $user->id,
                    'data'    => $draftData,
                ]);
            }

            $photoCount = count($draftData['draft_photos'] ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Brouillon enregistré' . ($photoCount ? " avec {$photoCount} photo(s)" : ''),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    // ── Récupérer tous les brouillons ────────────────────────

    public function getDrafts(): JsonResponse
    {
        $this->authorizeAdsAccess();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $drafts = AdDraft::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'drafts' => $drafts
        ]);
    }

    // ── Reprendre un brouillon spécifique ────────────────────

    public function resumeDraft($id): RedirectResponse
    {
        $this->authorizeAdsAccess();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $draft = AdDraft::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return redirect()->route('ads.create', ['draft_id' => $draft->id]);
    }

    // ── Supprimer un brouillon ───────────────────────────────

    public function deleteDraft($id): JsonResponse
    {
        $this->authorizeAdsAccess();

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $draft = AdDraft::where('id', $id)
                ->where('user_id', $user->id)
                ->first();

            $deleted = false;

            if ($draft) {
                foreach ($draft->data['draft_photos'] ?? [] as $draftPhotoPath) {
                    Storage::disk('public')->delete($draftPhotoPath);
                }
                $deleted = (bool) $draft->delete();
            }

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Brouillon supprimé avec succès'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Brouillon non trouvé'
                ], 404);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
