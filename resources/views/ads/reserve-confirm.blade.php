<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Déposez vos fonds — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { font-size: 16px; }
    body { font-family: 'Inter', sans-serif; background: #fff; color: rgb(21,34,51); min-height: 100vh; }
    a { text-decoration: none; color: inherit; }
    button, input { font-family: inherit; }

    .page { max-width: 430px; margin: 0 auto; min-height: 100vh; display: flex; flex-direction: column; background: #fff; }

    /* ── Topbar ── */
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
    .topbar-back svg { width: 24px; height: 24px; stroke: rgb(28,40,75); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .topbar-center {
        flex: 1;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        font-size: 17px; font-weight: 800; color: rgb(28,40,75);
        position: relative; height: 100%;
    }
    .topbar-center::after {
        content: '';
        position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 180px; height: 3px;
        background: rgb(28,40,75);
        border-radius: 2px 2px 0 0;
    }
    .topbar-center svg { width: 20px; height: 20px; flex-shrink: 0; }

    /* ── Carte Total ── */
    .total-card {
        background: #fff;
        border: 1px solid rgb(228,230,235);
        border-radius: 14px;
        margin: 16px 12px 0;
        padding: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .total-head {
        display: flex; justify-content: space-between; align-items: center;
    }
    .total-label { font-size: 16px; font-weight: 800; color: rgb(21,34,51); }
    .total-value { font-size: 17px; font-weight: 800; color: rgb(236,90,19); }
    .total-sub { font-size: 12px; font-weight: 700; color: rgb(28,40,75); margin-top: 2px; }
    .total-sep { height: 1px; background: rgb(228,230,235); margin: 12px 0; }
    .total-line {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 13px; color: rgb(45,55,72);
        padding: 3px 0;
    }
    .total-line-value { font-size: 13px; font-weight: 600; color: rgb(21,34,51); }

    /* ── Contenu ── */
    .content { padding: 20px 16px 0; display: flex; flex-direction: column; }

    .h1 { font-size: 22px; font-weight: 800; color: rgb(21,34,51); line-height: 1.3; margin-bottom: 18px; }
    .h2 { font-size: 15px; font-weight: 800; color: rgb(21,34,51); line-height: 1.4; margin-bottom: 10px; }
    .txt { font-size: 14px; color: rgb(45,55,72); line-height: 1.5; margin-bottom: 16px; }

    /* ── Champ Montant négocié (label flottant + suffixe €) ── */
    .amount-field {
        position: relative;
        border: 1.5px solid rgb(210,214,222);
        border-radius: 10px;
        padding: 8px 14px 10px;
        margin-bottom: 22px;
        display: flex; flex-direction: column;
        transition: border-color .15s;
    }
    .amount-field:focus-within { border-color: rgb(28,40,75); }
    .amount-label { font-size: 12px; color: rgb(100,110,125); margin-bottom: 2px; }
    .amount-row { display: flex; align-items: center; gap: 8px; }
    .amount-input {
        flex: 1; border: none; outline: none;
        font-size: 17px; font-weight: 600; color: rgb(21,34,51);
        background: transparent; min-width: 0;
        padding: 0;
    }
    .amount-suffix { font-size: 16px; color: rgb(21,34,51); flex-shrink: 0; }

    /* ── Moyen de paiement ── */
    .pay-card {
        display: flex; align-items: center; gap: 12px;
        border: 1px solid rgb(228,230,235);
        border-radius: 14px;
        padding: 14px 16px;
        cursor: pointer;
        background: #fff;
        width: 100%;
        text-align: left;
        transition: border-color .15s;
        margin-bottom: 22px;
    }
    .pay-card:active { border-color: rgb(28,40,75); }
    .pay-info { flex: 1; min-width: 0; }
    .pay-title { font-size: 15px; font-weight: 800; color: rgb(21,34,51); }
    .pay-sub { font-size: 12px; color: rgb(100,110,125); margin-top: 1px; }
    .pay-icons { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .pay-icons svg { width: 22px; height: 22px; }

    /* ── Lien annulation ── */
    .cancel-link {
        display: block; text-align: center;
        font-size: 14px; font-weight: 700;
        color: rgb(28,40,75);
        text-decoration: underline;
        margin-bottom: 22px;
        cursor: pointer;
    }

    /* ── Encadré bleu "plafond" ── */
    .info-blue {
        background: rgb(189,219,245);
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 22px;
    }
    .info-blue-title { font-size: 14px; font-weight: 800; color: rgb(28,40,75); margin-bottom: 6px; }
    .info-blue-txt { font-size: 13px; color: rgb(28,40,75); line-height: 1.5; }

    /* ── FAQ accordéons ── */
    .faq-wrap { display: flex; flex-direction: column; gap: 10px; padding-bottom: 32px; }
    .faq-item {
        background: #fff;
        border: 1px solid rgb(228,230,235);
        border-radius: 12px;
        overflow: hidden;
    }
    .faq-q {
        width: 100%; background: transparent; border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
        padding: 15px 16px;
        font-size: 14px; font-weight: 800; color: rgb(21,34,51);
        text-align: left; line-height: 1.4;
    }
    .faq-q svg { flex-shrink: 0; transition: transform .2s; width: 18px; height: 18px; stroke: rgb(28,40,75); fill: none; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .faq-item.open .faq-q svg { transform: rotate(180deg); }
    .faq-a { display: none; padding: 0 16px 15px; font-size: 13px; color: rgb(45,55,72); line-height: 1.5; }
    .faq-item.open .faq-a { display: block; }
    </style>
</head>
<body>
<div class="page">

    @php
        $vehiclePrice = (float)($ad->price ?? 0);
        $planPrice    = (float)($planInfo['price'] ?? 19.99);
        $total        = $vehiclePrice + $planPrice;

        $fmt = fn($v) => $v == intval($v)
            ? number_format($v, 0, ',', ' ').' €'
            : number_format($v, 2, ',', ' ').' €';
    @endphp

    {{-- ── Topbar ── --}}
    <div class="topbar">
        <button class="topbar-back" onclick="history.back()" type="button" aria-label="Retour">
            <svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
        </button>
        <div class="topbar-center">
            <svg viewBox="0 0 24 24" fill="none" stroke="rgb(28,40,75)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2l8 3v6c0 5-3.5 9.4-8 11-4.5-1.6-8-6-8-11V5l8-3z"/>
                <text x="12" y="14.5" text-anchor="middle" font-size="9" font-weight="700" fill="rgb(28,40,75)" stroke="none">€</text>
            </svg>
            Achat sécurisé
        </div>
    </div>

    {{-- ── Carte Total ── --}}
    <div class="total-card">
        <div class="total-head">
            <span class="total-label">Total</span>
            <span class="total-value" id="totalValue">{{ number_format($total, 2, ',', ' ') }} €</span>
        </div>
        <div class="total-sub">Détails du paiement</div>
        <div class="total-sep"></div>
        <div class="total-line">
            <span>Prix du véhicule</span>
            <span class="total-line-value" id="vehiclePriceLine">{{ $fmt($vehiclePrice) }}</span>
        </div>
        <div class="total-line">
            <span>Paiement sécurisé</span>
            <span class="total-line-value">{{ $fmt($planPrice) }}</span>
        </div>
    </div>

    {{-- ── Contenu ── --}}
    <div class="content">

        <div class="h1">Déposez vos fonds</div>

        <div class="h2">Avez-vous négocié le prix avec le vendeur ?</div>
        <div class="txt">Si une négociation est intervenue, vous pouvez modifier le montant ci-dessous :</div>

        {{-- Champ montant négocié --}}
        <div class="amount-field">
            <label class="amount-label" for="negotiated_amount">Montant négocié</label>
            <div class="amount-row">
                <input class="amount-input" type="number" inputmode="decimal" step="1" min="0"
                       id="negotiated_amount" placeholder="{{ intval($vehiclePrice) }}"
                       oninput="updateTotal(this.value)"/>
                <span class="amount-suffix">€</span>
            </div>
        </div>

        <div class="h2">Choisissez votre moyen de paiement</div>

        {{-- Moyen de paiement : virement --}}
        <button type="button" class="pay-card" onclick="goToVirement()">
            <div class="pay-info">
                <div class="pay-title">Virement bancaire</div>
                <div class="pay-sub">Délai de 2 à 3 jours ouvrés</div>
            </div>
            <div class="pay-icons">
                <svg viewBox="0 0 24 24" fill="none" stroke="rgb(28,40,75)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l8 3v6c0 5-3.5 9.4-8 11-4.5-1.6-8-6-8-11V5l8-3z"/>
                    <text x="12" y="14.5" text-anchor="middle" font-size="9" font-weight="700" fill="rgb(28,40,75)" stroke="none">€</text>
                </svg>
                <svg viewBox="0 0 24 24" fill="none" stroke="rgb(28,40,75)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
            </div>
        </button>

        {{-- Lien annulation --}}
        <a class="cancel-link" href="{{ url()->previous() }}">Je ne souhaite plus acheter le véhicule</a>

        {{-- Encadré bleu --}}
        <div class="info-blue">
            <div class="info-blue-title">Pensez à vérifier votre plafond</div>
            <div class="info-blue-txt">Le paiement par carte est maintenant disponible. Pensez à vérifier votre plafond de paiement par carte et à le modifier si besoin sur votre application bancaire.</div>
        </div>

        {{-- FAQ --}}
        <div class="faq-wrap">
            <div class="faq-item">
                <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                    Comment négocier le prix du véhicule ?
                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-a">Vous pouvez négocier directement avec le vendeur, avant ou après la réservation, et ce jusqu'à la remise des clés. Si un nouveau prix est convenu, indiquez simplement le montant négocié dans le champ ci-dessus.</div>
            </div>
            <div class="faq-item">
                <button class="faq-q" type="button" onclick="this.parentElement.classList.toggle('open')">
                    Comment négocier le prix du véhicule ?
                    <svg viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="faq-a">En cas d'accord avec le vendeur sur un nouveau prix, le montant déposé sur le compte séquestre est ajusté. Si vous avez déjà déposé vos fonds, la différence vous est remboursée automatiquement.</div>
            </div>
        </div>

    </div>
</div>

<script>
const vehiclePrice = {{ (float)($vehiclePrice) }};
const planPrice    = {{ (float)($planPrice) }};
const planKey      = '{{ $planKey }}';
const virementUrl  = '{{ route("ads.reserve.virement", $ad) }}';

function fmtEUR(v) {
    return v.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
}
function fmtEURshort(v) {
    return v.toLocaleString('fr-FR', {
        minimumFractionDigits: Number.isInteger(v) ? 0 : 2,
        maximumFractionDigits: Number.isInteger(v) ? 0 : 2
    }) + ' €';
}

function updateTotal(val) {
    const amount = val === '' ? vehiclePrice : (parseFloat(val) || vehiclePrice);
    document.getElementById('totalValue').textContent    = fmtEUR(amount + planPrice);
    document.getElementById('vehiclePriceLine').textContent = fmtEURshort(amount);
}

function goToVirement() {
    var raw        = document.getElementById('negotiated_amount').value;
    var vehicleAmt = raw === '' ? vehiclePrice : (parseFloat(raw) || vehiclePrice);
    var total      = vehicleAmt + planPrice;
    window.location.href = virementUrl + '?plan=' + encodeURIComponent(planKey) + '&amount=' + total;
}
</script>
</body>
</html>
