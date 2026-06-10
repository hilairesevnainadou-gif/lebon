<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=0"/>
    <title>Réserver — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body {
        font-family: 'Nunito Sans', sans-serif;
        background: #f5f4f0;
        color: #202730;
        min-height: 100vh;
    }
    input, select, textarea, button, a { font-family: inherit; }
    a { text-decoration: none; color: inherit; }

    :root {
        --main:       #ec5a13;
        --main-h:     #f07b42;
        --support:    #094171;
        --bg:         #f5f4f0;
        --surface:    #ffffff;
        --outline:    #d6d4cf;
        --muted:      #696969;
        --error:      #ad291f;
        --green:      #1e6c30;
        --green-bg:   #eaf5ed;
    }

    /* ── Layout ─────────────────────────────────── */
    .page { max-width: 430px; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; background: var(--bg); }

    /* ── Top bar ─────────────────────────────────── */
    .topbar {
        position: sticky; top: 0; z-index: 20;
        background: var(--surface);
        border-bottom: 1px solid var(--outline);
        height: 56px;
        display: flex; align-items: center; gap: 12px;
        padding: 0 16px;
    }
    .topbar-back {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: none; cursor: pointer;
        color: var(--support);
        transition: background .15s;
        flex-shrink: 0;
    }
    .topbar-back:active { background: #e6f2fd; }
    .topbar-back svg { width: 20px; height: 20px; }
    .topbar-title { font-size: 1rem; font-weight: 800; color: #202730; }

    /* ── Car summary card ────────────────────────── */
    .car-card {
        background: var(--surface);
        border-bottom: 1px solid var(--outline);
        display: flex; align-items: center; gap: 12px;
        padding: 14px 16px;
    }
    .car-thumb {
        width: 80px; height: 60px; border-radius: 8px;
        object-fit: cover; flex-shrink: 0;
        background: #e0e0e0;
    }
    .car-info { flex: 1; min-width: 0; }
    .car-title {
        font-size: .9rem; font-weight: 800;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        color: #202730;
    }
    .car-meta { font-size: .8rem; color: var(--muted); margin-top: 2px; }
    .car-price { font-size: 1.1rem; font-weight: 900; color: var(--main); margin-top: 4px; }

    /* ── Sections ────────────────────────────────── */
    .section { padding: 20px 16px 0; }
    .section-title {
        font-size: .7rem; font-weight: 900;
        text-transform: uppercase; letter-spacing: .8px;
        color: var(--muted);
        margin-bottom: 12px;
    }

    /* ── Form ────────────────────────────────────── */
    .form-group { margin-bottom: 12px; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .form-label {
        display: block;
        font-size: .8rem; font-weight: 800;
        color: #202730;
        margin-bottom: 5px;
    }
    .form-label .req { color: var(--main); }
    .form-input {
        width: 100%; height: 48px;
        border: 1.5px solid var(--outline);
        border-radius: 12px;
        padding: 0 14px;
        font-size: .95rem; font-weight: 600;
        color: #202730;
        background: var(--surface);
        outline: none;
        transition: border-color .15s;
        appearance: none;
    }
    .form-input:focus { border-color: var(--main); }
    .form-input.is-error { border-color: var(--error); }
    textarea.form-input { height: 96px; padding: 12px 14px; resize: none; }
    .field-error { font-size: .78rem; color: var(--error); font-weight: 700; margin-top: 4px; }

    /* ── Engagement mention ─────────────────────── */
    .engagement-box {
        margin: 16px 16px 0;
        background: var(--green-bg);
        border: 1.5px solid #b7e0be;
        border-radius: 14px;
        padding: 14px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .engagement-icon { width: 20px; height: 20px; fill: var(--green); flex-shrink: 0; margin-top: 1px; }
    .engagement-text { font-size: .82rem; font-weight: 700; color: var(--green); line-height: 1.4; }

    /* ── CTA ─────────────────────────────────────── */
    .cta-wrap { padding: 20px 16px 32px; }
    .btn-reserve-main {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; height: 56px;
        background: var(--main); color: #fff;
        border: none; border-radius: 99px;
        font-size: 1.1rem; font-weight: 900;
        cursor: pointer;
        transition: background .15s;
        letter-spacing: .2px;
    }
    .btn-reserve-main:active { background: var(--main-h); }
    .btn-reserve-main svg { width: 20px; height: 20px; fill: #fff; }
    .btn-reserve-main:disabled { opacity: .6; cursor: not-allowed; }

    .cta-legal {
        text-align: center;
        font-size: .75rem; color: var(--muted); font-weight: 600;
        margin-top: 12px; line-height: 1.5;
    }
    .cta-legal svg { width: 13px; height: 13px; vertical-align: -2px; fill: var(--muted); }

    /* ── Alert erreur globale ───────────────────── */
    .alert-error {
        margin: 16px 16px 0;
        background: #fdf0ef;
        border: 1.5px solid #f5bab6;
        border-radius: 12px;
        padding: 12px 14px;
        font-size: .85rem; font-weight: 700; color: var(--error);
        display: flex; gap: 8px; align-items: center;
    }
    .alert-error svg { width: 18px; height: 18px; fill: var(--error); flex-shrink: 0; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Top bar ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
        <span class="topbar-title">Réservation</span>
    </div>

    {{-- ── Résumé annonce ── --}}
    @php
        $mainPhoto = $ad->photos->first();
        $v = $ad->vehicle;
        $metaParts = array_filter([
            $v?->year,
            $v?->mileage ? number_format((int)$v->mileage, 0, ',', ' ') . ' km' : null,
            $v?->fuel_type,
        ]);
    @endphp
    <div class="car-card">
        @if($mainPhoto)
            <img class="car-thumb" src="{{ $mainPhoto->url }}" alt="{{ $ad->title }}"/>
        @else
            <div class="car-thumb" style="display:flex;align-items:center;justify-content:center;">
                <svg width="28" height="28" fill="none" stroke="#b0ada6" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
        @endif
        <div class="car-info">
            <div class="car-title">{{ $ad->title }}</div>
            @if(count($metaParts))
                <div class="car-meta">{{ implode(' · ', $metaParts) }}</div>
            @endif
            <div class="car-price">{{ $ad->formatted_price }}</div>
        </div>
    </div>

    {{-- ── Erreurs globales ── --}}
    @if($errors->any())
    <div class="alert-error">
        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13" stroke="#fff" stroke-width="2" fill="none"/><line x1="12" y1="17" x2="12.01" y2="17" stroke="#fff" stroke-width="2" fill="none"/></svg>
        Veuillez corriger les erreurs ci-dessous.
    </div>
    @endif

    {{-- ── Formulaire ── --}}
    <form method="POST" action="{{ route('ads.reserve.store', $ad) }}" id="reserveForm">
        @csrf
        <input type="hidden" name="plan" value="{{ $plan ?? 'sans_garantie' }}">
        @if(!empty($duration))
        <input type="hidden" name="duration" value="{{ $duration }}">
        @endif

        <div class="section">
            <div class="section-title">Vos coordonnées</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="first_name">Prénom <span class="req">*</span></label>
                    <input class="form-input {{ $errors->has('first_name') ? 'is-error' : '' }}"
                           type="text" id="first_name" name="first_name"
                           value="{{ old('first_name') }}" placeholder="Jean" autocomplete="given-name">
                    @error('first_name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="last_name">Nom <span class="req">*</span></label>
                    <input class="form-input {{ $errors->has('last_name') ? 'is-error' : '' }}"
                           type="text" id="last_name" name="last_name"
                           value="{{ old('last_name') }}" placeholder="Dupont" autocomplete="family-name">
                    @error('last_name')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Téléphone <span class="req">*</span></label>
                <input class="form-input {{ $errors->has('phone') ? 'is-error' : '' }}"
                       type="tel" id="phone" name="phone"
                       value="{{ old('phone') }}" placeholder="06 12 34 56 78" autocomplete="tel">
                @error('phone')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email <span class="req">*</span></label>
                <input class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                       type="email" id="email" name="email"
                       value="{{ old('email') }}" placeholder="jean@exemple.fr" autocomplete="email">
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="message">Message (optionnel)</label>
                <textarea class="form-input {{ $errors->has('message') ? 'is-error' : '' }}"
                          id="message" name="message"
                          placeholder="Précisez vos disponibilités, questions...">{{ old('message') }}</textarea>
                @error('message')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── Engagement ── --}}
        <div class="engagement-box">
            <svg class="engagement-icon" viewBox="0 0 24 24">
                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="engagement-text">
                En réservant ce véhicule, le vendeur sera notifié et vous contactera pour fixer un rendez-vous. Aucun paiement n'est requis à ce stade.
            </div>
        </div>

        {{-- ── CTA ── --}}
        <div class="cta-wrap">
            <button type="submit" class="btn-reserve-main" id="submitBtn">
                <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Réserver mon véhicule
            </button>
            <p class="cta-legal">
                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Vos données restent confidentielles et ne sont partagées qu'avec le vendeur.
            </p>
        </div>

    </form>

</div>
<script>
document.getElementById('reserveForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"><circle cx="12" cy="12" r="10" opacity=".3"/><path d="M12 2a10 10 0 0110 10" style="animation:spin 1s linear infinite;transform-origin:center"/></svg> Envoi en cours…';
});
</script>
</body>
</html>
