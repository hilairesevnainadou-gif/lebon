<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
    <meta name="theme-color" content="#ffffff"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="default"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <title>Choisir ma formule — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { font-size: 16px; height: 100%; overflow: hidden; }
    body { font-family: 'Inter', sans-serif; background: #fff; color: rgb(21,34,51); overscroll-behavior: none; }
    a { text-decoration: none; color: inherit; }
    button, input { font-family: inherit; }

    .page { position: fixed; inset: 0; max-width: 430px; margin: 0 auto; display: flex; flex-direction: column; background: #fff; overflow: hidden; padding-top: env(safe-area-inset-top); }
    .scroll-body { flex: 1; overflow-y: auto; -webkit-overflow-scrolling: touch; overscroll-behavior: none; }

    /* ── Top bar : titre centré + icône, trait sous le titre ── */
    .topbar {
        position: sticky; top: 0; z-index: 20;
        background: #fff; height: 56px;
        display: flex; align-items: center;
        padding: 0 16px; flex-shrink: 0;
        border-bottom: 1px solid rgb(228,230,235);
    }
    .topbar-back {
        position: absolute; left: 10px;
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: none; cursor: pointer; padding: 0;
    }
    .topbar-back svg { width: 24px; height: 24px; stroke: rgb(28,40,75); }
    .topbar-center {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        font-size: 17px; font-weight: 800; color: rgb(28,40,75);
        position: relative; height: 100%;
    }
    .topbar-center::after {
        content: '';
        position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 170px; height: 3px;
        background: rgb(28,40,75);
        border-radius: 2px 2px 0 0;
    }
    .topbar-center svg { width: 20px; height: 20px; flex-shrink: 0; }

    /* ── Résumé véhicule (sans carte, sur fond blanc) ── */
    .vehicle-row {
        display: flex; gap: 12px; align-items: flex-start;
        padding: 16px 16px 0;
    }
    .vehicle-thumb {
        width: 72px; height: 54px; border-radius: 8px;
        object-fit: cover; flex-shrink: 0;
        background: rgb(225,225,225);
        display: flex; align-items: center; justify-content: center;
    }
    .vehicle-info { flex: 1; min-width: 0; }
    .vehicle-title { font-size: 14px; font-weight: 800; line-height: 1.35; color: rgb(28,40,75); }
    .vehicle-price { font-size: 15px; font-weight: 700; margin-top: 6px; color: rgb(21,34,51); }

    /* ── Titre + partenaire ── */
    .sec-title { font-size: 17px; font-weight: 800; color: rgb(21,34,51); padding: 20px 16px 4px; }
    .partner-row {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 16px 4px;
        font-size: 13px; color: rgb(21,34,51);
    }
    .partner-row img { height: 26px; width: auto; object-fit: contain; }

    /* ── Cartes de formule ── */
    .plans-wrap { padding: 10px 12px 0; display: flex; flex-direction: column; gap: 14px; }

    .plan-card {
        background: #fff;
        border: 1px solid rgb(228,230,235);
        border-radius: 16px;
        padding: 18px 16px;
        cursor: pointer;
        outline: 2.5px solid transparent;
        transition: outline-color .15s, border-color .15s;
        position: relative;
    }
    .plan-card.selected { outline-color: rgb(236,90,19); border-color: transparent; }
    .plan-card.selected .plan-check { background: rgb(236,90,19); border-color: rgb(236,90,19); }
    .plan-card.selected .plan-check::after { display: block; }

    /* Badge "La plus populaire" */
    .badge-popular {
        position: absolute; top: -11px; left: 14px;
        background: rgb(236,90,19); color: #fff;
        font-size: 11px; font-weight: 700;
        padding: 4px 10px; border-radius: 100px;
        line-height: 1;
    }

    .plan-header-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .plan-check {
        width: 20px; height: 20px; border-radius: 50%;
        border: 2px solid rgb(190,195,205);
        background: #fff; flex-shrink: 0;
        position: relative; transition: all .15s;
    }
    .plan-check::after {
        content: ''; display: none;
        position: absolute; top: 3px; left: 3px;
        width: 10px; height: 10px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpolyline points='2 6 5 9 10 3' stroke='white' stroke-width='2' fill='none'/%3E%3C/svg%3E") center/contain no-repeat;
    }
    .plan-name { font-size: 15px; font-weight: 800; color: rgb(21,34,51); flex: 1; }
    .plan-price-tag { font-size: 17px; font-weight: 800; color: rgb(236,90,19); flex-shrink: 0; }

    .plan-features { display: flex; flex-direction: column; gap: 9px; }
    .feat { display: flex; align-items: flex-start; gap: 9px; font-size: 13.5px; color: rgb(45,55,72); line-height: 1.4; }
    .feat-icon { flex-shrink: 0; margin-top: 1px; }
    .plan-txt { font-size: 13px; color: rgba(21,34,51,.7); line-height: 1.5; }

    .voir-plus-btn {
        background: transparent; border: none; cursor: pointer;
        font-size: 14px; font-weight: 700; color: rgb(28,40,75);
        padding: 12px 0 0; width: 100%; text-align: center;
    }

    /* ── Liens documents ── */
    .doc-links { padding: 16px 16px 0; display: flex; flex-direction: column; gap: 4px; }
    .doc-links a { font-size: 13px; color: rgb(21,34,51); text-decoration: underline; line-height: 1.6; }

    /* ── Bon à savoir (encadré gris) ── */
    .bonsavoir {
        background: rgb(240,242,245);
        border-radius: 14px;
        margin: 16px 16px 0;
        padding: 16px;
    }
    .bonsavoir-title {
        display: flex; align-items: center; gap: 7px;
        font-size: 14px; font-weight: 800; color: rgb(21,34,51);
        margin-bottom: 6px;
    }
    .bonsavoir-txt { font-size: 13px; color: rgb(45,55,72); line-height: 1.5; }

    /* ── Bouton Continuer (pilule, largeur auto, aligné à gauche) ── */
    .btn-continuer {
        display: inline-flex; align-items: center; justify-content: center;
        background: rgb(236,90,19); color: #fff;
        border: none; border-radius: 100px;
        font-size: 16px; font-weight: 700; cursor: pointer;
        padding: 14px 28px;
        margin: 20px 16px 0;
        align-self: flex-start;
        transition: opacity .15s;
    }
    .btn-continuer:active { opacity: .85; }

    /* ── Mentions légales / partenaire bas de page ── */
    .legal-block { padding: 24px 16px 0; }
    .legal-txt { font-size: 12px; color: rgba(21,34,51,.65); line-height: 1.5; margin-top: 10px; }
    .legal-txt a { text-decoration: underline; font-weight: 600; color: rgb(21,34,51); }

    /* ── FAQ accordéons ── */
    .faq-wrap { padding: 20px 12px 24px; display: flex; flex-direction: column; gap: 10px; }
    .faq-item {
        background: #fff;
        border: 1px solid rgb(228,230,235);
        border-radius: 14px;
        overflow: hidden;
    }
    .faq-q {
        width: 100%; background: transparent; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 16px;
        font-size: 14px; font-weight: 800; color: rgb(21,34,51);
        text-align: left; line-height: 1.4;
    }
    .faq-q svg { flex-shrink: 0; transition: transform .2s; width: 18px; height: 18px; stroke: rgb(28,40,75); }
    .faq-item.open .faq-q svg { transform: rotate(180deg); }
    .faq-a { display: none; padding: 0 16px 16px; font-size: 13px; color: rgb(45,55,72); line-height: 1.5; }
    .faq-item.open .faq-a { display: block; }

    /* ── Footer ── */
    .footer {
        flex-shrink: 0;
        background: rgb(15,24,37); color: #fff;
        padding: 16px 16px max(env(safe-area-inset-bottom, 0px), 16px);
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        font-size: 13px; font-weight: 600;
    }
    .footer-stars { display: flex; align-items: center; gap: 6px; font-size: 12px; }
    .footer-stars .stars { display: flex; gap: 2px; }
    .footer-stars .star {
        width: 16px; height: 16px;
        background: rgb(0,182,122);
        display: flex; align-items: center; justify-content: center;
    }
    </style>
</head>
<body>
<div class="page">

    @php
        $mainPhoto = $ad->photos->first();
        $carPrice = (float)($ad->price ?? 0);
        $priceFormatted = $carPrice > 0 ? number_format($carPrice, 0, ',', ' ').' €' : '';

        $features = [
            'Couverture mécanique',
            'Aucun frais à avancer',
            "Dépannage et mise à disposition d'un véhicule",
            'Franchise et remboursement',
            'Accès à des conseils téléphoniques',
            'Réservation du véhicule',
            'Paiement sécurisé',
        ];

        $allPlans = [
            'garantie_3'    => ['name' => 'La Garantie 3 mois',           'price' => 139,   'popular' => false, 'no_warranty' => false],
            'garantie_6'    => ['name' => 'La Garantie 6 mois',           'price' => 259,   'popular' => false, 'no_warranty' => false],
            'garantie_12'   => ['name' => 'La Garantie 12 mois',          'price' => 399,   'popular' => true,  'no_warranty' => false],
            'sans_garantie' => ['name' => 'Sans Garantie Panne Mécanique','price' => 19.99, 'popular' => false, 'no_warranty' => true],
        ];
        $selectedPlan = old('plan', $plan ?? 'garantie_3');
        if (!array_key_exists($selectedPlan, $allPlans)) $selectedPlan = 'garantie_3';
    @endphp

    {{-- ── Top bar ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <div class="topbar-center">
            <svg viewBox="0 0 24 24" fill="none" stroke="rgb(28,40,75)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l8 3v6c0 5-3.5 9.4-8 11-4.5-1.6-8-6-8-11V5l8-3z"/>
                <text x="12" y="14.5" text-anchor="middle" font-size="9" font-weight="700" fill="rgb(28,40,75)" stroke="none">€</text>
            </svg>
            Achat sécurisé
        </div>
    </div>

    <div class="scroll-body">
    {{-- ── Résumé véhicule ── --}}
    <div class="vehicle-row">
        @if($mainPhoto)
            <img class="vehicle-thumb" src="{{ $mainPhoto->url }}" alt="{{ $ad->title }}"/>
        @else
            <div class="vehicle-thumb">
                <svg width="24" height="24" fill="none" stroke="rgba(21,34,51,.25)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
        @endif
        <div class="vehicle-info">
            <div class="vehicle-title">{{ $ad->title }}</div>
            @if($priceFormatted)<div class="vehicle-price">{{ $priceFormatted }}</div>@endif
        </div>
    </div>

    {{-- ── Titre + partenaire ── --}}
    <div class="sec-title">Choisissez votre formule :</div>
    <div class="partner-row">
        <span>En partenariat avec</span>
        <img src="{{ asset('/RESERVE/bnpp-cardif.avif') }}" alt="BNP Paribas Cardif"/>
    </div>

    {{-- ── Formulaire + cartes ── --}}
    <form method="POST" action="{{ route('ads.reserve.store', $ad) }}" id="mainForm">
        @csrf
        <input type="hidden" name="plan" id="hiddenPlan" value="{{ $selectedPlan }}"/>

        <div class="plans-wrap">
            @foreach($allPlans as $key => $p)
            <div class="plan-card {{ $selectedPlan === $key ? 'selected' : '' }}"
                 id="card-{{ $key }}"
                 onclick="selectPlan('{{ $key }}')"
                 style="{{ $p['popular'] ? 'margin-top:8px;' : '' }}">
                @if($p['popular'])
                    <span class="badge-popular">La plus populaire</span>
                @endif
                <div class="plan-header-row">
                    <div class="plan-check"></div>
                    <div class="plan-name">{{ $p['name'] }}</div>
                    <div class="plan-price-tag">
                        {{ $p['price'] == intval($p['price'])
                            ? number_format($p['price'], 0, ',', ' ')
                            : number_format($p['price'], 2, ',', ' ') }} €
                    </div>
                </div>
                @if($p['no_warranty'])
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div class="plan-txt">Assurez-vous que le véhicule ne vous passe pas sous le nez.</div>
                        <div class="plan-txt">Votre argent est protégé sur un compte séquestre jusqu'au jour de la transaction.</div>
                        <div class="plan-features" style="margin-top:2px;">
                            @foreach(['Réservation du véhicule','Paiement sécurisé'] as $feat)
                            <div class="feat">
                                <span class="feat-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgb(100,110,125)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                                {{ $feat }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                @else
                <div class="plan-features">
                    @foreach($features as $feat)
                    <div class="feat">
                        <span class="feat-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="rgb(100,110,125)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                        {{ $feat }}
                    </div>
                    @endforeach
                </div>
                <button class="voir-plus-btn" type="button" onclick="event.stopPropagation()">Voir plus</button>
                @endif
            </div>
            @endforeach
        </div>

        {{-- ── Liens documents ── --}}
        <div class="doc-links">
            <a href="#">Plus de détails sur la garantie</a>
            <a href="#">Document d'information sur les produits d'assurance</a>
            <a href="#">Fiche d'information pré-contractuelle</a>
            <a href="#">Conditions générales de la garantie</a>
        </div>

        {{-- ── Bon à savoir ── --}}
        <div class="bonsavoir">
            <div class="bonsavoir-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgb(21,34,51)" stroke-width="2" stroke-linecap="round"><path d="M9 18h6M10 21h4M12 3a6 6 0 00-4 10.5c.6.6 1 1.5 1 2.5h6c0-1 .4-1.9 1-2.5A6 6 0 0012 3z"/></svg>
                Bon à savoir
            </div>
            <div class="bonsavoir-txt">Le montant de la formule choisie sera débité lorsque le vendeur aura validé la réservation. Si la vente est annulée, vous récupérez l'intégralité de la somme versée.</div>
        </div>

        {{-- ── Bouton Continuer ── --}}
        <button class="btn-continuer" type="button" onclick="goToRecap()">Continuer</button>
    </form>

    {{-- ── Mentions partenaire ── --}}
    <div class="legal-block">
        <div class="partner-row" style="padding:0;">
            <span>En partenariat avec</span>
            <img src="{{ asset('/RESERVE/bnpp-cardif.avif') }}" alt="BNP Paribas Cardif"/>
        </div>
        <div class="legal-txt" id="legalTxt">
            Ma garantie Maximale, Optimale et Essentielle sont des produits proposés par CARDIF Assurances R...
            <a href="#" onclick="document.getElementById('legalTxt').innerHTML = LEGAL_FULL; return false;">Voir tout</a>
        </div>
    </div>

    {{-- ── FAQ ── --}}
    <div class="faq-wrap">
        <div class="faq-item">
            <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                Qu'est ce que la Garantie Panne Mécanique ?
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="faq-a">La Garantie Panne Mécanique couvre les frais de réparation des pannes mécaniques, électriques et électroniques de votre véhicule pendant la durée choisie (3, 6 ou 12 mois), selon les conditions du contrat.</div>
        </div>
        <div class="faq-item">
            <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                En cas de panne, dois-je avancer les frais de réparation de mon véhicule ?
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </button>
            <div class="faq-a">Non, vous n'avez aucun frais à avancer. Le réparateur agréé est directement payé par l'assureur, dans la limite des plafonds prévus au contrat.</div>
        </div>
    </div>

    </div>{{-- /scroll-body --}}

    {{-- ── Footer ── --}}
    <div class="footer">
        <span>leboncoin 2006 - {{ date('Y') }}</span>
        <span class="footer-stars">
            <span class="stars">
                @for($i = 0; $i < 4; $i++)
                <span class="star"><svg width="11" height="11" viewBox="0 0 24 24" fill="#fff"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7L2 9.2l7.1-.6L12 2z"/></svg></span>
                @endfor
                <span class="star" style="background:rgb(70,80,95);"><svg width="11" height="11" viewBox="0 0 24 24" fill="#fff"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.7-6.2 3.7 1.6-7L2 9.2l7.1-.6L12 2z"/></svg></span>
            </span>
            263656 avis su
        </span>
    </div>

</div>

<script>
const LEGAL_FULL = "Ma garantie Maximale, Optimale et Essentielle sont des produits proposés par CARDIF Assurances Risques Divers, entreprise régie par le Code des assurances. Voir les conditions générales pour le détail des couvertures, exclusions et plafonds.";

function selectPlan(key) {
    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
    document.getElementById('card-' + key).classList.add('selected');
    document.getElementById('hiddenPlan').value = key;
}

function goToRecap() {
    var plan = document.getElementById('hiddenPlan').value || 'sans_garantie';
    window.location.href = '{{ route("ads.reserve.recap", $ad) }}?plan=' + encodeURIComponent(plan);
}
</script>
</body>
</html>
