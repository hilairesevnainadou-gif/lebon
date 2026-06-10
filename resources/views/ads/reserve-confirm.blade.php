<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=0"/>
    <title>Réservation confirmée — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; }
    body { font-family: 'Nunito Sans', sans-serif; background: #f5f4f0; color: #202730; min-height: 100vh; }
    a { text-decoration: none; color: inherit; }

    :root {
        --main:    #ec5a13;
        --main-h:  #f07b42;
        --support: #094171;
        --bg:      #f5f4f0;
        --surface: #ffffff;
        --outline: #d6d4cf;
        --muted:   #696969;
        --green:   #1e6c30;
        --green-bg:#eaf5ed;
    }

    .page { max-width: 430px; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; background: var(--bg); }

    /* ── Top bar ── */
    .topbar {
        position: sticky; top: 0; z-index: 20;
        background: var(--surface); border-bottom: 1px solid var(--outline);
        height: 56px; display: flex; align-items: center; gap: 12px; padding: 0 16px;
    }
    .topbar-back { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: transparent; border: none; cursor: pointer; color: var(--support); flex-shrink: 0; }
    .topbar-back svg { width: 20px; height: 20px; }
    .topbar-title { font-size: 1rem; font-weight: 800; }

    /* ── Hero confirmation ── */
    .confirm-hero {
        background: var(--surface);
        border-bottom: 1px solid var(--outline);
        padding: 32px 24px 28px;
        text-align: center;
    }
    .confirm-circle {
        width: 72px; height: 72px; border-radius: 50%;
        background: var(--green-bg);
        border: 2px solid #b7e0be;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
    }
    .confirm-circle svg { width: 36px; height: 36px; fill: none; stroke: var(--green); stroke-width: 2.5; }
    .confirm-title { font-size: 1.3rem; font-weight: 900; color: var(--green); }
    .confirm-sub { font-size: .9rem; color: var(--muted); font-weight: 600; margin-top: 6px; line-height: 1.5; }

    /* ── Car card ── */
    .car-card {
        background: var(--surface); border-bottom: 1px solid var(--outline);
        display: flex; align-items: center; gap: 12px; padding: 14px 16px;
    }
    .car-thumb { width: 80px; height: 60px; border-radius: 8px; object-fit: cover; flex-shrink: 0; background: #e0e0e0; }
    .car-info { flex: 1; min-width: 0; }
    .car-title { font-size: .9rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .car-meta { font-size: .8rem; color: var(--muted); margin-top: 2px; }
    .car-price { font-size: 1.1rem; font-weight: 900; color: var(--main); margin-top: 4px; }

    /* ── Info bloc ── */
    .info-section { padding: 20px 16px 0; }
    .info-title { font-size: .7rem; font-weight: 900; text-transform: uppercase; letter-spacing: .8px; color: var(--muted); margin-bottom: 12px; }
    .info-card { background: var(--surface); border: 1.5px solid var(--outline); border-radius: 14px; overflow: hidden; }
    .info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 13px 16px; border-bottom: 1px solid var(--outline);
        gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: .82rem; color: var(--muted); font-weight: 700; flex-shrink: 0; }
    .info-value { font-size: .9rem; font-weight: 800; color: #202730; text-align: right; word-break: break-all; }

    /* ── Ref box ── */
    .ref-box {
        margin: 16px 16px 0;
        background: #f0f4ff;
        border: 1.5px solid #c8d8f8;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex; gap: 10px; align-items: center;
    }
    .ref-icon { width: 20px; height: 20px; fill: var(--support); flex-shrink: 0; }
    .ref-content {}
    .ref-label { font-size: .75rem; font-weight: 700; color: var(--support); text-transform: uppercase; letter-spacing: .5px; }
    .ref-value { font-size: 1rem; font-weight: 900; color: #202730; letter-spacing: 1px; margin-top: 2px; font-variant-numeric: tabular-nums; }

    /* ── CTAs ── */
    .cta-wrap { padding: 20px 16px 40px; display: flex; flex-direction: column; gap: 10px; }
    .btn-primary-full {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        height: 52px; border-radius: 99px;
        background: var(--main); color: #fff;
        border: none; font-size: 1rem; font-weight: 900;
        cursor: pointer; transition: background .15s;
        text-decoration: none;
    }
    .btn-primary-full:active { background: var(--main-h); }
    .btn-outline-full {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        height: 52px; border-radius: 99px;
        background: transparent; color: var(--support);
        border: 1.5px solid var(--support); font-size: 1rem; font-weight: 900;
        cursor: pointer; transition: background .15s;
        text-decoration: none;
    }
    .btn-outline-full:active { background: #e6f2fd; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Top bar ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <span class="topbar-title">Confirmation</span>
    </div>

    {{-- ── Hero ── --}}
    <div class="confirm-hero">
        <div class="confirm-circle">
            <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <div class="confirm-title">Réservation confirmée !</div>
        <div class="confirm-sub">
            Le vendeur a été notifié et vous contactera<br>dans les plus brefs délais.
        </div>
    </div>

    {{-- ── Véhicule ── --}}
    @php
        $mainPhoto = $ad->photos->first();
        $v = $ad->vehicle;
        $metaParts = array_filter([$v?->year, $v?->mileage ? number_format((int)$v->mileage,0,',', ' ').' km' : null, $v?->fuel_type]);
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

    {{-- ── Référence dossier ── --}}
    <div class="ref-box">
        <svg class="ref-icon" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <div class="ref-content">
            <div class="ref-label">Numéro de dossier</div>
            <div class="ref-value">#{{ strtoupper(substr($reservation->token, 0, 8)) }}</div>
        </div>
    </div>

    {{-- ── Coordonnées saisies ── --}}
    <div class="info-section">
        <div class="info-title">Vos informations</div>
        <div class="info-card">
            <div class="info-row">
                <span class="info-label">Prénom</span>
                <span class="info-value">{{ $reservation->first_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nom</span>
                <span class="info-value">{{ $reservation->last_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone</span>
                <span class="info-value">{{ $reservation->phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ $reservation->email }}</span>
            </div>
            @if($reservation->message)
            <div class="info-row" style="flex-direction:column; align-items:flex-start; gap:6px;">
                <span class="info-label">Message</span>
                <span class="info-value" style="text-align:left; word-break:break-word;">{{ $reservation->message }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- ── CTAs ── --}}
    <div class="cta-wrap">
        <a href="{{ route('ads.public', ['c' => $ad->share_token]) }}" class="btn-primary-full">
            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
            Voir l'annonce
        </a>
        <a href="/" class="btn-outline-full">
            Retour à l'accueil
        </a>
    </div>

</div>
</body>
</html>
