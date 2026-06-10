@php
    // ── Charger le HTML de référence (selectionner-ma-formule-garantie)
    $refFile = resource_path('views/reserveform-reference.html');
    $h = file_get_contents($refFile);

    // 1. Remplacer le chemin _files/ par /reserveform/
    $h = str_replace(
        './Peugeot 308 II 1.5 BlueHDi 130ch BVA EAT8 Actif – Excellent état - Voitures_files/',
        '/reserveform/',
        $h
    );
    $h = str_replace(
        './Peugeot 308 II 1.5 BlueHDi 130ch BVA EAT8 Actif - Excellent état - Voitures_files/',
        '/reserveform/',
        $h
    );

    // 2. Supprimer l'extension .télécharger
    $h = str_replace('.télécharger', '', $h);

    // 3. Corriger la référence au fichier CSS (css → fonts.css)
    $h = str_replace('href="/reserveform/css"', 'href="/reserveform/fonts.css"', $h);
    $h = str_replace("href='/reserveform/css'", "href='/reserveform/fonts.css'", $h);

    // 4. Supprimer TOUS les scripts Bubble.io
    $h = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $h);
    $h = preg_replace('/<script\b[^>]*\/>/i', '', $h);

    // 5. Calcul du plan sélectionné et de l'addition
    $planKey = $plan ?? 'sans_garantie';
    $plans = [
        'sans_garantie'    => ['label' => 'Sans Garantie Panne Mécanique', 'price' => 19.99,  'duration' => null],
        'avec_garantie_3'  => ['label' => 'Avec Garantie Panne Mécanique', 'price' => 139,    'duration' => '3 mois'],
        'avec_garantie_6'  => ['label' => 'Avec Garantie Panne Mécanique', 'price' => 259,    'duration' => '6 mois'],
        'avec_garantie_12' => ['label' => 'Avec Garantie Panne Mécanique', 'price' => 399,    'duration' => '12 mois'],
    ];
    if (!array_key_exists($planKey, $plans)) $planKey = 'sans_garantie';
    $planData  = $plans[$planKey];
    $planPrice = $planData['price'];
    $carPrice  = (float)($ad->price ?? 0);
    $total     = $carPrice + $planPrice;

    $planPriceFmt = $planPrice == intval($planPrice)
        ? number_format($planPrice, 0, ',', ' ') . ' €'
        : number_format($planPrice, 2, ',', ' ') . ' €';
    $carPriceFmt  = number_format($carPrice, 0, ',', ' ') . ' €';
    $totalFmt     = number_format($total, 2, ',', ' ') . ' €';
    $durationTxt  = $planData['duration'] ? ' — ' . $planData['duration'] : '';

    // 6. Injecter le bandeau de récap + addition avant </body>
    $inject = '
<style>
#plan-recap-bar{
    position:fixed;bottom:0;left:50%;transform:translateX(-50%);
    width:100%;max-width:430px;
    background:#fff;
    box-shadow:0 -2px 12px rgba(0,0,0,.12);
    z-index:9999;
    font-family:"Inter",sans-serif;
    border-top:1px solid rgb(228,230,235);
}
.prb-plan{
    background:rgb(255,233,222);
    padding:10px 16px;
    display:flex;align-items:center;justify-content:space-between;gap:8px;
}
.prb-plan-name{font-size:13px;font-weight:700;color:rgb(137,56,15);}
.prb-plan-price{font-size:15px;font-weight:800;color:rgb(137,56,15);flex-shrink:0;}
.prb-amounts{
    padding:8px 16px;
    display:flex;flex-direction:column;gap:4px;
}
.prb-row{display:flex;justify-content:space-between;font-size:12px;color:rgba(21,34,51,.65);}
.prb-row span:last-child{font-weight:700;color:rgb(21,34,51);}
.prb-total{
    display:flex;justify-content:space-between;align-items:center;
    padding:10px 16px;
    border-top:1px solid rgb(228,230,235);
    font-size:14px;font-weight:800;color:rgb(21,34,51);
}
.prb-btn{
    margin:0 16px 14px;
    display:flex;align-items:center;justify-content:center;gap:8px;
    background:rgb(236,90,19);color:#fff;
    border:none;border-radius:15px;
    font-family:"Inter",sans-serif;font-size:16px;font-weight:700;
    min-height:50px;width:calc(100% - 32px);cursor:pointer;
    text-decoration:none;
}
</style>
<div id="plan-recap-bar">
    <div class="prb-plan">
        <span class="prb-plan-name">' . htmlspecialchars($planData['label'] . $durationTxt) . '</span>
        <span class="prb-plan-price">' . $planPriceFmt . '</span>
    </div>
    <div class="prb-amounts">
        <div class="prb-row"><span>Prix du véhicule</span><span>' . $carPriceFmt . '</span></div>
        <div class="prb-row"><span>Formule choisie</span><span>' . $planPriceFmt . '</span></div>
    </div>
    <div class="prb-total">
        <span>Total à prévoir</span>
        <span>' . $totalFmt . '</span>
    </div>
    <a href="' . route('ads.reserve.form', $ad) . '?plan=' . urlencode($planKey) . '"
       class="prb-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            <polyline points="9 12 11 14 15 10"/>
        </svg>
        Réserver mon véhicule
    </a>
</div>
';

    // Ajouter padding-bottom au body pour ne pas masquer le contenu
    $h = str_replace('<body', '<body style="padding-bottom:220px"', $h);

    $h = str_replace('</body>', $inject . '</body>', $h);

    echo $h;
@endphp
