<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Ad;
use App\Models\AdDraft;
use App\Models\AdFeature;
use App\Models\AdLike;
use App\Models\Reservation;
use App\Models\AdPhoto;
use App\Models\Seller;
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
    // ── Liste des annonces (publiées + brouillons) ────────────

    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Récupérer les annonces publiées (admin = tout, sinon seulement les siennes)
        $publishedAds = $user->isAdmin()
            ? Ad::with(['vehicle', 'photos'])->latest()->get()
            : Ad::with(['vehicle', 'photos'])
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
        $currentPage = request()->get('page', 1);
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

    // ── Formulaire de création ────────────────────────────────

    public function create(Request $request): View
    {
        $draftData = null;

        // Vérifier si on reprend un brouillon
        if ($request->has('draft_id')) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $draft = AdDraft::where('id', $request->draft_id)
                ->where('user_id', $user->id)
                ->first();

            if ($draft) {
                $draftData = $draft->data;
                // Optionnel: supprimer le brouillon après récupération
                // $draft->delete();
            }
        } elseif (session('draft')) {
            $draftData = session('draft');
        }

        return view('ads.create', compact('draftData'));
    }

    // ── Publication de l'annonce ──────────────────────────────

    public function store(StoreAdRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        /** @var Ad|null $ad */
        $ad = null;

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. Récupérer les données du vendeur
            $sellerData = $request->input('seller');

            $seller = Seller::updateOrCreate(
                ['user_id' => $user->id, 'email' => $sellerData['email']],
                [
                    'first_name' => $sellerData['first_name'],
                    'last_name'  => $sellerData['last_name'],
                    'phone'      => $sellerData['phone'],
                    'city'       => $sellerData['city'],
                ]
            );

            // 2. Compte bancaire
            $bankData = $request->input('bank');
            $cleanedIban = preg_replace('/\s+/', '', (string) $bankData['iban']);

            $existingBank = $seller->bankAccounts()
                ->where('iban', $cleanedIban)
                ->first();

            if (!$existingBank) {
                $seller->bankAccounts()->create([
                    'iban'                => $cleanedIban,
                    'bic'                 => strtoupper((string) $bankData['bic']),
                    'bank_name'           => $bankData['bank_name'] ?? null,
                    'account_holder_name' => $bankData['account_holder_name'],
                    'is_default'          => true,
                ]);
            }

            // 3. Créer l'annonce
            $adData = $request->input('ad');

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

            // 4. Véhicule
            $vehicleData = $request->input('vehicle');
            Vehicle::create(array_merge($vehicleData, ['ad_id' => $ad->id]));

            // 5. Équipements
            if ($request->filled('features')) {
                $features = $request->input('features');
                AdFeature::syncForAd($ad->id, $features);
            }

            // 6. Photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $index => $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $directory = 'ads/' . $ad->id . '/photos';
                    $path = $file->storeAs($directory, $filename, 'public');

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

            // 7. Supprimer le brouillon associé
            if ($request->has('draft_id')) {
                AdDraft::where('id', $request->draft_id)
                    ->where('user_id', $user->id)
                    ->delete();
            }

            DB::commit();

            return redirect()
                ->route('ads.show', $ad)
                ->with('success', 'Votre annonce a été publiée avec succès !');

        } catch (\Throwable $e) {
            DB::rollBack();

            if ($ad instanceof Ad) {
                AdPhoto::deleteAllForAd($ad->id);
                $ad->delete();
            }

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    // ── Formulaire de modification ───────────────────────────

    public function edit(Ad $ad): View
    {
        $ad->load(['vehicle', 'photos', 'features']);
        return view('ads.edit', compact('ad'));
    }

    // ── Enregistrement des modifications ─────────────────────

    public function update(UpdateAdRequest $request, Ad $ad): RedirectResponse
    {
        $adData      = $request->input('ad');
        $vehicleData = $request->input('vehicle');

        // Mise à jour de l'annonce
        $ad->update([
            'title'        => $adData['title'],
            'description'  => $adData['description'] ?? null,
            'price'        => $adData['price'],
            'city'         => $adData['city'],
            'postal_code'  => $adData['postal_code'] ?? null,
            'likes_count'  => (int) ($adData['likes_count'] ?? 0),
            'status'       => $adData['status'],
        ]);

        // Mise à jour du véhicule
        if ($ad->vehicle) {
            $ad->vehicle->update($vehicleData);
        }

        // Mise à jour des équipements
        AdFeature::syncForAd($ad->id, $request->input('features', []));

        // Ajout de nouvelles photos
        if ($request->hasFile('photos')) {
            $existingCount = $ad->photos()->count();
            foreach ($request->file('photos') as $index => $file) {
                if ($existingCount + $index >= 12) break;
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $path     = $file->storeAs('ads/' . $ad->id . '/photos', $filename, 'public');
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

        return redirect()->route('ads.show', $ad)
            ->with('success', 'Annonce mise à jour avec succès.');
    }

    // ── Réorganisation des photos ────────────────────────────

    public function reorderPhotos(Ad $ad, Request $request): JsonResponse
    {
        $ids = $request->input('order', []);
        foreach ($ids as $position => $photoId) {
            $ad->photos()->where('id', (int) $photoId)->update(['order' => $position]);
        }
        return response()->json(['success' => true]);
    }

    // ── Suppression d'une photo ──────────────────────────────

    public function destroyPhoto(Ad $ad, AdPhoto $photo): JsonResponse
    {
        if ($photo->ad_id !== $ad->id) {
            abort(403);
        }
        $photo->deleteWithFile();
        return response()->json(['success' => true, 'remaining' => $ad->photos()->count()]);
    }

    // ── Vue privée d'une annonce ─────────────────────────────

    public function show(Ad $ad): View
    {
        $ad->load(['seller', 'vehicle', 'photos', 'features']);

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
        $ad->load(['vehicle', 'photos', 'seller']);
        $ip         = request()->ip();
        $isLiked    = $ad->isLikedByIp($ip);
        $likesTotal = $ad->likes()->count() + ($ad->likes_count ?? 0);
        return view('ads.reserve', compact('ad', 'isLiked', 'likesTotal'));
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
        $ad->load(['vehicle', 'photos', 'seller.defaultBankAccount']);
        $planKey  = request()->query('plan', 'sans_garantie');
        $planInfo = $this->reservationPlans[$planKey] ?? $this->reservationPlans['sans_garantie'];
        // amount reçu = total déjà calculé (vehicle + plan) depuis reserve-confirm
        $total       = (float) request()->query('amount', ($ad->price ?? 0) + $planInfo['price']);
        $amount      = $total - $planInfo['price'];
        $bankAccount = $ad->seller?->defaultBankAccount;

        return view('ads.reserve-done', compact('ad', 'planInfo', 'planKey', 'amount', 'total', 'bankAccount'));
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
        $ad->status = $ad->status === 'active' ? 'paused' : 'active';
        $ad->save();

        $label = $ad->status === 'active' ? 'réactivée' : 'désactivée';

        return redirect()->route('ads.show', $ad)
            ->with('success', "L'annonce a été {$label} avec succès.");
    }

    // ── Générer le lien public ───────────────────────────────

    public function share(Ad $ad): RedirectResponse
    {
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

        $ad = Ad::with(['seller', 'vehicle', 'photos', 'features'])
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
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $deleted = AdDraft::where('id', $id)
                ->where('user_id', $user->id)
                ->delete();

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
