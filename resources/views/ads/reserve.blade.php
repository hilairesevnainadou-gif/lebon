<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Réserver — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { font-size: 16px; height: 100%; }
    body {
        font-family: 'Inter', sans-serif;
        background: #fff;                       /* fond blanc comme le visuel */
        color: rgb(21, 34, 51);
        min-height: 100vh;
    }
    a { text-decoration: none; color: inherit; }
    button, input { font-family: inherit; }

    /* ── Page wrapper (mobile-first, max 430px) ── */
    .page {
        max-width: 430px;
        margin: 0 auto;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    /* ── Top bar : flèche à gauche, titre centré avec icône ── */
    .topbar {
        position: sticky; top: 0; z-index: 20;
        background: #fff;
        height: 56px;
        display: flex; align-items: center;
        padding: 0 16px;
        flex-shrink: 0;
    }
    .topbar-back {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: none; cursor: pointer;
        color: rgb(21, 34, 51);
        flex-shrink: 0;
        padding: 0;
        position: absolute; left: 10px;
    }
    .topbar-back svg { width: 24px; height: 24px; stroke: rgb(28,40,75); }
    .topbar-title {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        font-size: 18px; font-weight: 800;
        color: rgb(28, 40, 75);              /* bleu nuit comme le visuel */
    }
    .topbar-title svg { width: 22px; height: 22px; flex-shrink: 0; }

    /* ── Barre de progression sous la topbar ── */
    .progress {
        height: 5px;
        background: rgb(229, 232, 238);
        width: 100%;
        flex-shrink: 0;
    }
    .progress-fill {
        height: 100%;
        width: 20%;                          /* étape 1/5 — ajuster selon l'étape */
        background: rgb(43, 57, 105);
        border-radius: 0 3px 3px 0;
    }

    /* ── Hero illustration ── */
    .hero {
        width: 100%;
        padding: 16px 0 8px;
        display: flex; justify-content: center;
    }
    .hero img {
        width: 100%; max-width: 380px;
        height: auto; display: block;
    }

    /* ── Logo Pack Sérénité ── */
    .pack-logo { padding: 8px 20px 0; }

    /* ── Contenu principal (sur fond blanc, sans carte) ── */
    .main-section {
        padding: 16px 20px 0;
        display: flex; flex-direction: column; row-gap: 22px;
    }

    .h1 { font-size: 26px; font-weight: 800; color: rgb(21,34,51); line-height: 1.25; }
    .h2 { font-size: 19px; font-weight: 800; color: rgb(21,34,51); line-height: 1.4; }

    /* ── Steps (scroll horizontal, cartes bordées claires) ── */
    .steps-scroll {
        display: flex; flex-direction: row; gap: 14px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 2px;
    }
    .steps-scroll::-webkit-scrollbar { display: none; }

    .step-card {
        background: #fff;
        border: 1px solid rgb(228, 230, 235);   /* bordure fine claire (visuel) */
        border-radius: 16px;
        padding: 20px 18px;
        display: flex; flex-direction: column; row-gap: 16px;
        min-width: 220px; max-width: 250px;
        flex-shrink: 0;
        width: calc(60vw);
    }
    @media (min-width: 380px) { .step-card { width: 230px; } }

    .step-num {
        width: 40px; height: 40px;
        background: rgb(236, 90, 19);
        color: #fff;
        font-size: 19px; font-weight: 700;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .step-title { font-size: 18px; font-weight: 800; color: rgb(21,34,51); line-height: 1.3; }
    .step-desc  { font-size: 15px; font-weight: 400; color: rgb(45,55,72); line-height: 1.5; flex: 1; }

    /* ── Plan cards ── */
    .plans-wrap { display: flex; flex-direction: column; row-gap: 12px; }

    .plan-card {
        background: #fff;
        border: 1px solid rgb(228, 230, 235);
        border-radius: 16px;
        padding: 20px 15px;
        display: flex; flex-direction: column; row-gap: 10px;
    }

    .plan-header {
        background: rgb(255, 233, 222);
        border-radius: 16px;
        padding: 20px;
        width: 100%;
    }
    .plan-header-title {
        font-size: 14px; font-weight: 700;
        color: rgb(137, 56, 15);
        text-align: center; line-height: 1;
    }

    .durations { display: flex; justify-content: center; gap: 5px; align-items: stretch; }
    .dur-col { flex: 1; min-width: 40px; max-width: 70px; display: flex; flex-direction: column; gap: 2px; padding: 0 5px; }
    .dur-label { font-size: 12px; font-weight: 600; color: rgb(21,34,51); line-height: 1.4; }
    .dur-price { font-size: 16px; font-weight: 700; color: rgb(21,34,51); line-height: 1.4; }
    .dur-sep   { width: 2px; background: rgb(242,242,242); flex-shrink: 0; border-radius: 1px; }
    .dur-divider { width: 100%; height: 2px; background: rgb(242,242,242); border-radius: 1px; }

    .partner-row { display: flex; align-items: center; gap: 6px; font-size: 12px; color: rgb(15,24,37); }

    .feat { display: flex; align-items: flex-start; gap: 7px; font-size: 13px; color: rgb(15,24,37); line-height: 1.4; }
    .feat-icon { flex-shrink: 0; margin-top: 1px; }

    .plan-txt { font-size: 13px; color: rgb(15,24,37); line-height: 1.4; }
    .plan-price { font-size: 16px; font-weight: 700; color: rgb(21,34,51); text-align: center; line-height: 1.4; padding: 6px 0; }
    .plan-link { font-size: 12px; color: rgb(21,34,51); text-decoration: underline; line-height: 1.6; display: block; cursor: pointer; }
    .voir-plus-btn { background: transparent; border: none; cursor: pointer; font-size: 14px; font-weight: 600; color: rgb(15,24,37); padding: 0; align-self: flex-start; text-decoration: underline; }

    /* ── Sélection interactive de formule ── */
    .dur-col.dur-clickable { cursor: pointer; border-radius: 8px; padding: 4px 5px; transition: background .15s; }
    .dur-col.dur-clickable:hover { background: rgb(255,233,222); }
    .dur-col.dur-active { background: rgb(255,233,222); }
    .dur-col.dur-active .dur-label,
    .dur-col.dur-active .dur-price { color: rgb(137,56,15); }

    .selectable-plan { cursor: pointer; transition: outline .15s; outline: 2px solid transparent; }
    .selectable-plan.selected { outline: 2px solid rgb(236,90,19); }

    .plan-select-indicator {
        display: none;
        align-items: center; gap: 6px;
        background: rgb(236,90,19);
        color: #fff;
        font-size: 13px; font-weight: 700;
        padding: 8px 14px;
        border-radius: 10px;
        margin-top: 4px;
        width: fit-content;
    }
    .selectable-plan.selected .plan-select-indicator { display: flex; }

    /* ── Bon à savoir ── */
    .bonsavoir {
        background: #fff;
        border: 1px solid rgb(228, 230, 235);
        border-radius: 16px;
        margin: 16px 20px 0;
        padding: 20px;
        display: flex; flex-direction: column; row-gap: 10px;
    }
    .bonsavoir-title { font-size: 16px; font-weight: 700; }
    .bonsavoir-txt   { font-size: 13px; color: rgb(15,24,37); line-height: 1.4; }

    /* ── CTA ── */
    .cta-wrap { padding: 20px 20px 36px; display: flex; flex-direction: column; gap: 12px; }
    .cgu-txt { font-size: 12px; color: rgba(21,34,51,0.7); line-height: 1.2; }
    .cgu-txt a { text-decoration: underline; }
    .btn-reserve {
        display: flex; align-items: center; justify-content: center;
        width: 100%;
        background: rgb(236, 90, 19); color: #fff;
        font-size: 16px; font-weight: 600; line-height: 1.4;
        border-radius: 15px; padding: 0 20px; min-height: 48px;
        border: none; cursor: pointer; transition: opacity .15s;
    }
    .btn-reserve:active { opacity: .85; }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Top bar : titre centré + icône bouclier € ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/>
            </svg>
        </button>
        <span class="topbar-title">
            <svg viewBox="0 0 24 24" fill="none" stroke="rgb(28,40,75)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l8 3v6c0 5-3.5 9.4-8 11-4.5-1.6-8-6-8-11V5l8-3z"/>
                <text x="12" y="14.5" text-anchor="middle" font-size="9" font-weight="700" fill="rgb(28,40,75)" stroke="none">€</text>
            </svg>
            Achat sécurisé
        </span>
    </div>

    {{-- ── Barre de progression ── --}}
    <div class="progress"><div class="progress-fill"></div></div>

    {{-- ── Illustration hero ── --}}
    <div class="hero">
        <img src="/reserve/hero-handshake.svg" alt="" aria-hidden="true"/>
    </div>

    {{-- ── Logo Pack Sérénité ── --}}
    <div class="pack-logo">
        <img src="/RESERVE/pack.avif" alt="Pack Sérénité" style="height:38px;width:auto;object-fit:contain;"/>
    </div>

    {{-- ── Section principale ── --}}
    <div class="main-section">

        <div class="h1">Acheter votre véhicule en toute confiance</div>

        <div>
            <div class="h2" style="margin-bottom: 14px;">Comment ça marche ?</div>

            <div class="steps-scroll">
                <div class="step-card">
                    <div class="step-num">1</div>
                    <div class="step-title">Réservez le véhicule</div>
                    <div class="step-desc">Avant ou après avoir vu le véhicule, je le réserve pour rassurer le vendeur sur ma volonté d'acheter son véhicule. Si je le souhaite, je peux négocier le prix, et ce jusqu'à la remise des clés.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">2</div>
                    <div class="step-title">Préparez votre paiement en ligne</div>
                    <div class="step-desc">Je sélectionne mon moyen de paiement et dépose mes fonds. Mon argent reste bloqué et sécurisé sur un compte séquestre jusqu'à la vente.</div>
                </div>
                <div class="step-card">
                    <div class="step-num">3</div>
                    <div class="step-title">Payer votre vendeur en toute sécurité</div>
                    <div class="step-desc">Lors de la remise des clés, je débloque les fonds au vendeur. Nous sommes instantanément notifiés de la disponibilité des fonds. En cas d'annulation ou de négociation, je récupère tout ou partie de mon paiement.</div>
                </div>
            </div>
        </div>

        {{-- ── Les formules ── --}}
        <div>
            <div class="h2" style="margin-bottom: 12px;">Les formules</div>

            <div class="plans-wrap">

                {{-- ── Avec Garantie ── --}}
                <div class="plan-card selectable-plan" id="plan-avec" data-plan="avec_garantie_3" onclick="selectPlan(this)">
                    <div class="plan-header">
                        <div class="plan-header-title">Avec Garantie Panne Mécanique</div>
                    </div>

                    <div class="durations">
                        <div class="dur-col dur-clickable {{ true ? 'dur-active' : '' }}" data-plan="avec_garantie_3" onclick="event.stopPropagation();pickDur(this)">
                            <div class="dur-label">3 mois</div><div class="dur-price">139 €</div>
                        </div>
                        <div class="dur-sep"></div>
                        <div class="dur-col dur-clickable" data-plan="avec_garantie_6" onclick="event.stopPropagation();pickDur(this)">
                            <div class="dur-label">6 mois</div><div class="dur-price">259 €</div>
                        </div>
                        <div class="dur-sep"></div>
                        <div class="dur-col dur-clickable" data-plan="avec_garantie_12" onclick="event.stopPropagation();pickDur(this)">
                            <div class="dur-label">12 mois</div><div class="dur-price">399 €</div>
                        </div>
                    </div>

                    <div class="dur-divider"></div>

                    <div class="partner-row">
                        <span>En partenariat avec</span>
                        <img src="{{ asset('assets/reserve/bnpp-cardif.avif') }}" alt="BNPP Cardif" height="22" style="height:22px;width:auto;object-fit:contain;"/>
                    </div>

                    @foreach([
                        'Aucun frais à avancer',
                        'Couverture mécanique',
                        "Dépannage et mise à disposition d'un véhicule",
                        'Franchise et remboursement',
                        'Accès à des conseils téléphoniques',
                        'Réservation du véhicule',
                    ] as $feat)
                    <div class="feat">
                        <span class="feat-icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgb(30,108,48)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></span>
                        {{ $feat }}
                    </div>
                    @endforeach

                    <button class="voir-plus-btn" type="button" onclick="event.stopPropagation()">Voir plus</button>
                    <div class="plan-select-indicator"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff"><polyline points="20 6 9 17 4 12"/></svg> Sélectionnée</div>
                </div>

                {{-- ── Sans Garantie ── --}}
                <div class="plan-card selectable-plan" id="plan-sans" data-plan="sans_garantie" onclick="selectPlan(this)">
                    <div class="plan-header">
                        <div class="plan-header-title">Sans Garantie Panne Mécanique</div>
                    </div>

                    <div class="plan-price">19,99 €</div>

                    <div class="plan-txt">Assurez-vous que le véhicule ne vous passe pas sous le nez.</div>
                    <div class="plan-txt">Votre argent est protégé sur un compte séquestre jusqu'au jour de la transaction.</div>

                    <div style="display:flex;flex-direction:column;gap:2px;margin-top:4px;">
                        <a class="plan-link" href="#" onclick="event.stopPropagation()">Plus de détails sur la garantie</a>
                        <a class="plan-link" href="#" onclick="event.stopPropagation()">Document d'information sur les produits d'assurance</a>
                        <a class="plan-link" href="#" onclick="event.stopPropagation()">Fiche d'information pré-contractuelle</a>
                        <a class="plan-link" href="#" onclick="event.stopPropagation()">Conditions générales de la garantie</a>
                    </div>
                    <div class="plan-select-indicator"><svg width="16" height="16" viewBox="0 0 24 24" fill="#fff"><polyline points="20 6 9 17 4 12"/></svg> Sélectionnée</div>
                </div>

            </div>
        </div>

    </div>{{-- /main-section --}}

    {{-- ── Bon à savoir ── --}}
    <div class="bonsavoir">
        <div class="bonsavoir-title">Bon à savoir : restez vigilants</div>
        <div class="bonsavoir-txt">Une fois la réservation acceptée par le vendeur, le RIB Leboncoin sera accessible via l'étape suivante. Il ne vous sera jamais envoyé par e-mail, SMS, image, capture d'écran, photographie ou lien.</div>
        <div class="bonsavoir-txt">Pour une transaction en toute sécurité et éviter les fraudes, suivez nos recommandations : <u>en savoir plus</u></div>
    </div>

    {{-- ── CTA ── --}}
    <div class="cta-wrap">
        <div class="cgu-txt">
            En cliquant sur «&nbsp;Réserver mon véhicule&nbsp;», <a href="#">j'accepte les Conditions Générales d'Utilisation.</a>
        </div>
        <a href="{{ route('ads.reserve.form', $ad) }}?plan=sans_garantie"
           id="ctaBtn" class="btn-reserve">
            Réserver mon véhicule
        </a>
    </div>

</div>
<script>
const BASE_URL = '{{ route('ads.reserve.form', $ad) }}';
let currentPlan = 'sans_garantie';

function selectPlan(card) {
    document.querySelectorAll('.selectable-plan').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    currentPlan = card.dataset.plan;
    updateCta();
}

function pickDur(col) {
    // Déselectionner les autres colonnes dans la même carte
    col.closest('.plan-card').querySelectorAll('.dur-clickable').forEach(c => c.classList.remove('dur-active'));
    col.classList.add('dur-active');
    // Activer la carte parente
    const card = col.closest('.selectable-plan');
    selectPlan(card);
    currentPlan = col.dataset.plan;
    card.dataset.plan = col.dataset.plan;
    updateCta();
}

function updateCta() {
    document.getElementById('ctaBtn').href = BASE_URL + '?plan=' + encodeURIComponent(currentPlan);
}

// Sélectionner la formule Sans Garantie par défaut
document.getElementById('plan-sans').click();
</script>
</body>
</html>
