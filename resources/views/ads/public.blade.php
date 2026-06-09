<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=0"/>
    <title>{{ $ad->title }} — {{ $ad->formatted_price }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet"/>
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
        --font: 'Nunito Sans', sans-serif;
        --main:        #ec5a13;
        --main-hover:  #f07b42;
        --support:     #094171;
        --support-bg:  #e6f2fd;
        --support-txt: #152233;
        --surface:     #ffffff;
        --on-surface:  #202730;
        --bg:          #f5f4f0;
        --outline:     #d6d4cf;
        --outline-h:   #b0ada6;
        --neutral:     #696969;
        --neutral-bg:  #ebebeb;
        --error:       #ad291f;
        --cetelem:     #00a651;
    }

    html { font-size: 62.5%; }
    body {
        font-family: var(--font);
        font-size: 1.6rem;
        background: var(--bg);
        color: var(--on-surface);
        max-width: 430px;
        margin: 0 auto;
        min-height: 100vh;
        -webkit-text-size-adjust: 100%;
    }
    a { color: inherit; text-decoration: none; }
    button { font-family: var(--font); cursor: pointer; border: none; background: none; }
    img { display: block; max-width: 100%; }
    .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; }

    /* ══ STICKY BAR ══════════════════════════════ */
    .sticky-bar {
        position: fixed; top: 0; left: 50%; transform: translateX(-50%) translateY(-100%);
        width: 100%; max-width: 430px;
        background: var(--surface);
        box-shadow: 0 2px 8px rgba(0,0,0,.12);
        z-index: 200;
        transition: transform .25s cubic-bezier(.2,0,0,1);
    }
    .sticky-bar.visible { transform: translateX(-50%) translateY(0); }
    .sticky-inner {
        padding: 8px 16px;
        display: flex; align-items: center; gap: 12px;
        height: 64px;
    }
    .sticky-thumb {
        width: 64px; height: 48px; border-radius: 6px;
        object-fit: cover; flex-shrink: 0;
    }
    .sticky-info { flex: 1; min-width: 0; }
    .sticky-title {
        font-size: 1.4rem; font-weight: 800;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        line-height: 1.3;
    }
    .sticky-price { font-size: 1.6rem; font-weight: 900; }
    .sticky-fav {
        width: 44px; height: 44px; border-radius: 8px;
        background: var(--surface);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .sticky-fav svg { width: 20px; height: 20px; fill: var(--error); }

    /* ══ RETOUR ABSOLU ════════════════════════════ */
    .btn-back {
        position: absolute; top: 16px; left: 16px; z-index: 20;
        width: 44px; height: 44px; border-radius: 8px;
        background: var(--surface);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 1px 4px rgba(0,0,0,.18);
    }
    .btn-back svg { width: 20px; height: 20px; fill: var(--on-surface); }

    /* ══ ACTIONS HAUT DROITE ═════════════════════ */
    .gallery-actions {
        position: absolute; top: 16px; right: 16px; z-index: 20;
        display: flex; gap: 8px;
    }
    .btn-action {
        height: 44px; padding: 0 14px;
        background: var(--surface); border-radius: 8px;
        display: flex; align-items: center; gap: 6px;
        box-shadow: 0 1px 4px rgba(0,0,0,.18);
        font-size: 1.5rem; font-weight: 800; color: var(--on-surface);
    }
    .btn-action svg { width: 20px; height: 20px; fill: var(--on-surface); }
    .fav-red svg { fill: var(--error); }

    /* ══ CAROUSEL ════════════════════════════════ */
    .carousel-wrap { position: relative; background: var(--neutral-bg); }
    .carousel-track {
        display: grid; grid-auto-flow: column; grid-auto-columns: 100%;
        overflow-x: auto; scroll-snap-type: x mandatory;
        scrollbar-width: none;
        height: 380px;
    }
    .carousel-track::-webkit-scrollbar { display: none; }
    .carousel-slide {
        scroll-snap-align: start; scroll-snap-stop: always;
        background: var(--neutral-bg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .carousel-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .carousel-footer {
        position: absolute; bottom: 60px; left: 16px; right: 16px;
        display: flex; align-items: flex-end; justify-content: space-between;
        pointer-events: none; z-index: 10;
    }
    .btn-similar {
        display: flex; align-items: center; gap: 8px;
        background: var(--support); color: #fff;
        height: 44px; padding: 0 16px; border-radius: 8px;
        font-size: 1.4rem; font-weight: 800;
        pointer-events: all;
    }
    .btn-similar svg { width: 20px; height: 20px; fill: #fff; }
    .badge-counter {
        display: inline-flex; align-items: center; gap: 5px;
        background: var(--support); color: #fff;
        height: 20px; padding: 0 8px; border-radius: 99px;
        font-size: 1.2rem; font-weight: 800;
        pointer-events: all;
    }
    .badge-counter svg { width: 14px; height: 14px; fill: #fff; }
    .badge-counter .light { font-weight: 400; }
    .carousel-dots {
        position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%);
        display: flex; gap: 5px; z-index: 10;
    }
    .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: rgba(255,255,255,.5); transition: background .2s;
    }
    .dot.active { background: #fff; }

    /* ══ TITLE CARD — CARTE FLOTTANTE (CORRIGÉ) ═══ */
    .title-card {
        position: relative;
        z-index: 15;
        margin: -28px 16px 0;                  /* chevauche la photo + marges gauche/droite */
        background: var(--surface);
        border-radius: 16px;                   /* arrondi sur les 4 coins */
        box-shadow: 0 4px 20px rgba(0,0,0,.12);
        padding: 24px 22px 22px;
    }
    .ad-title {
        font-size: 2.2rem; font-weight: 900; line-height: 1.25;
        margin-bottom: 12px; word-break: break-word;
        color: var(--on-surface);
    }
    .ad-meta {
        font-size: 1.6rem; color: var(--neutral);
        margin-bottom: 16px;
        display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
        line-height: 1.4;
    }
    .ad-meta a { color: var(--neutral); }
    .ad-meta .sep { color: var(--outline-h); }
    .ad-price {
        font-size: 2.9rem; font-weight: 900; letter-spacing: -0.5px;
        margin-bottom: 10px; color: var(--on-surface);
    }
    .ad-date { font-size: 1.4rem; color: var(--neutral); margin-bottom: 16px; }
    .tag-secure {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--support-bg); color: var(--support-txt);
        height: 30px; padding: 0 12px; border-radius: 99px;
        font-size: 1.4rem; font-weight: 700;
    }
    .tag-secure svg { width: 15px; height: 15px; fill: var(--support); }

    /* ══ BOUTON RÉSERVER — HORS CARTE, SUR FOND GRIS (CORRIGÉ) ═══ */
    .reserve-section {
        background: transparent;
        padding: 20px 16px 0;
    }
    .btn-reserve {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; height: 56px;
        background: var(--main); color: #fff;
        border-radius: 99px;
        font-size: 1.7rem; font-weight: 900;
        letter-spacing: 0.2px;
        transition: background .15s;
    }
    .btn-reserve:active { background: var(--main-hover); }
    .btn-reserve svg { width: 20px; height: 20px; fill: #fff; }

    /* séparateur fin sous le bouton (CORRIGÉ) */
    .cta-divider { height: 1px; background: var(--outline); margin: 20px 16px 0; }

    /* ══ DIVIDERS ════════════════════════════════ */
    .divider-thick {
        height: 8px; background: var(--bg);
        border-top: 1px solid var(--outline);
        border-bottom: 1px solid var(--outline);
    }
    .divider-thin { height: 1px; background: var(--outline); margin: 0 16px; }

    /* ══ PUBLICITÉ ═══════════════════════════════ */
    .ad-pub-slot {
        padding: 20px 16px;
        border-bottom: 1px solid var(--outline);
        display: flex; justify-content: center;
        position: relative;
        background: var(--surface);
    }
    .ad-pub-placeholder {
        background: #e8e7e3;
        border-radius: 99px;
        width: 100%;
        padding: 16px 0;
        display: flex; flex-direction: column; align-items: center; gap: 2px;
    }
    .pub-logo { font-size: 1.8rem; font-weight: 700; color: #5a5a6a; letter-spacing: -0.3px; font-family: var(--font); }
    .pub-label { font-size: 1rem; font-style: italic; color: #9a9aaa; letter-spacing: .2px; }
    .pub-slot-real { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; }

    /* ══ SECTIONS ════════════════════════════════ */
    .section { background: var(--surface); padding: 24px 16px; border-top: 1px solid var(--outline); }
    .section-pay { background: var(--surface); padding: 40px 16px 24px; border-bottom: 1px solid var(--outline); }
    .section-h2 { font-size: 2rem; font-weight: 900; margin-bottom: 16px; display: flex; align-items: center; color: var(--on-surface); }

    /* ══ PAIEMENT SÉCURISÉ ═══════════════════════ */
    .pay-text { font-size: 1.4rem; line-height: 1.6; margin-bottom: 16px; }
    .pay-list { list-style: none; margin-bottom: 16px; }
    .pay-item { display: flex; align-items: flex-start; gap: 8px; margin-top: 8px; }
    .pay-item svg { width: 16px; height: 16px; fill: var(--support); flex-shrink: 0; margin-top: 2px; }
    .pay-item p { font-size: 1.4rem; line-height: 1.6; }
    .link-more { font-size: 1.4rem; font-weight: 800; color: var(--on-surface); text-decoration: underline; text-underline-offset: 2px; display: inline-block; margin-top: 8px; }

    /* ══ CRITÈRES ════════════════════════════════ */
    .criteria-list { display: flex; flex-direction: column; }
    .criteria-row { display: flex; align-items: center; padding: 10px 0; gap: 10px; }
    .criteria-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .criteria-icon svg { width: 22px; height: 22px; fill: var(--on-surface); opacity: .45; }
    .criteria-left { flex: 1; }
    .criteria-lbl { font-size: 1.4rem; color: var(--neutral); font-weight: 400; }
    .criteria-val { font-size: 1.6rem; font-weight: 900; color: var(--on-surface); text-align: right; }
    .criteria-val a { font-size: 1.6rem; font-weight: 900; text-decoration: underline; text-underline-offset: 2px; color: var(--on-surface); }
    .btn-more-criteria { display: inline-block; margin-top: 8px; font-size: 1.4rem; font-weight: 700; text-decoration: underline; text-underline-offset: 2px; color: var(--on-surface); padding: 0; background: none; border: none; cursor: pointer; }

    /* ══ DESCRIPTION ═════════════════════════════ */
    .desc-text { font-size: 1.4rem; line-height: 1.75; color: var(--on-surface); white-space: pre-line; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .desc-full { display: none; white-space: pre-line; font-size: 1.4rem; line-height: 1.75; }
    .btn-desc-more { margin-top: 8px; font-size: 1.4rem; font-weight: 800; text-decoration: underline; text-underline-offset: 2px; padding: 0; }

    /* ══ FINANCEMENT ═════════════════════════════ */
    .tag-sponsored { display: inline-flex; align-items: center; border: 1px solid var(--outline-h); color: var(--on-surface); height: 28px; padding: 0 10px; border-radius: 99px; font-size: 1.3rem; font-weight: 400; margin-bottom: 12px; }
    .fin-disclaimer { font-size: 1.4rem; color: var(--on-surface); opacity: .55; line-height: 1.5; margin-bottom: 16px; }
    .fin-subtitle { font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; color: var(--on-surface); }
    .fin-cetelem-row { display: flex; align-items: center; gap: 6px; margin-bottom: 20px; }
    .fin-avec { font-size: 1.4rem; color: var(--on-surface); }
    .fin-cetelem { font-size: 1.9rem; font-weight: 800; font-style: italic; color: var(--cetelem); font-family: Georgia, serif; text-decoration: underline; text-underline-offset: 3px; text-decoration-color: var(--cetelem); }
    .fin-input-wrap { margin-bottom: 20px; }
    .fin-input-label { font-size: 1.4rem; font-weight: 400; display: block; margin-bottom: 8px; color: var(--on-surface); }
    .fin-input-row { position: relative; }
    .fin-input { width: 100%; height: 48px; border: 1px solid var(--outline); border-radius: 8px; padding: 0 40px 0 16px; font-size: 1.4rem; font-family: var(--font); color: var(--on-surface); background: var(--surface); outline: none; }
    .fin-input::placeholder { color: var(--neutral); opacity: .7; }
    .fin-input:focus { border-color: var(--support); }
    .fin-euro { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; fill: var(--neutral); pointer-events: none; }
    .fin-duration-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .fin-dur-label { font-size: 1.4rem; color: var(--on-surface); }
    .fin-dur-val { font-size: 1.5rem; font-weight: 800; color: var(--support); }
    .fin-slider { -webkit-appearance: none; width: 100%; height: 3px; border-radius: 2px; background: linear-gradient(to right, var(--support) 24.6%, #d6d4cf 24.6%); outline: none; margin-bottom: 6px; }
    .fin-slider::-webkit-slider-thumb { -webkit-appearance: none; width: 22px; height: 22px; border-radius: 50%; background: var(--support); border: 2px solid #fff; box-shadow: 0 1px 4px rgba(0,0,0,.25); cursor: pointer; }
    .fin-limits { display: flex; justify-content: space-between; font-size: 1.2rem; color: var(--neutral); margin-bottom: 0; }
    .btn-simulate { display: flex; align-items: center; justify-content: center; width: 100%; height: 52px; border: 1.5px solid var(--support); border-radius: 8px; color: var(--support); background: var(--surface); font-size: 1.6rem; font-weight: 700; font-family: var(--font); margin: 20px 0 0; transition: background .15s; }
    .btn-simulate:active { background: var(--support-bg); }
    .fin-legal { font-size: 1.1rem; color: var(--neutral); line-height: 1.5; opacity: .65; margin-top: 16px; }

    /* ══ LOCALISATION ════════════════════════════ */
    .loc-city { font-size: 1.6rem; font-weight: 800; margin-bottom: 16px; display: flex; align-items: center; gap: 4px; }
    .loc-city span { font-size: 1.4rem; font-weight: 400; color: var(--neutral); }
    .map-box { width: 100%; height: 220px; border-radius: 8px; background: #d4e6c3; overflow: hidden; position: relative; cursor: pointer; }
    .map-box img { width: 100%; height: 100%; object-fit: cover; }

    /* ══ VENDEUR ══════════════════════════════════ */
    .seller-top {
        display: flex; align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .seller-ava {
        width: 56px; height: 56px; border-radius: 50%;
        background: var(--main);
        overflow: hidden; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .seller-ava img { width: 100%; height: 100%; object-fit: cover; }
    .seller-ava-initial {
        font-size: 2.2rem; font-weight: 900;
        color: #fff;
        line-height: 1;
        text-transform: uppercase;
        font-family: var(--font);
    }
    .btn-follow {
        flex-shrink: 0;
        height: 36px; padding: 0 20px;
        border-radius: 99px;
        border: 1.5px solid var(--support);
        color: var(--support); font-weight: 800;
        font-size: 1.4rem; font-family: var(--font);
        background: transparent;
        display: flex; align-items: center;
        transition: background .15s;
    }
    .btn-follow:active { background: var(--support-bg); }
    .seller-name {
        font-size: 1.8rem; font-weight: 900;
        color: var(--on-surface);
        display: block;
        margin-bottom: 14px;
        text-decoration: none;
    }
    .seller-info-list { list-style: none; margin-bottom: 20px; }
    .seller-info-item {
        display: flex; align-items: center; gap: 8px;
        margin-top: 6px; font-size: 1.4rem; color: var(--neutral);
    }
    .seller-info-item svg { width: 16px; height: 16px; fill: var(--neutral); opacity: .6; flex-shrink: 0; }
    .seller-badges { display: flex; gap: 24px; margin-bottom: 4px; }
    .badge-btn {
        display: flex; flex-direction: column; align-items: center;
        gap: 6px; background: none; border: none; cursor: pointer; padding: 0;
        width: 72px;
    }
    .badge-circle {
        width: 56px; height: 56px; border-radius: 50%;
        background: #ffe9de;
        display: flex; align-items: center; justify-content: center;
    }
    .badge-circle svg { width: 26px; height: 26px; fill: #89380f; }
    .badge-lbl { font-size: 1.1rem; text-align: center; line-height: 1.3; color: var(--on-surface); font-weight: 400; }

    /* ══ FOOTER LINKS ════════════════════════════ */
    .footer-links { padding: 24px 16px; border-top: 1px solid var(--outline); background: var(--surface); display: flex; flex-direction: column; gap: 24px; }
    .footer-link { display: flex; align-items: center; gap: 8px; font-size: 1.4rem; font-weight: 700; text-decoration: underline; text-underline-offset: 2px; }
    .footer-link svg { width: 16px; height: 16px; fill: currentColor; }

    /* ══ BREADCRUMB ══════════════════════════════ */
    .breadcrumb-wrap { padding: 16px; background: var(--surface); border-top: 1px solid var(--outline); }
    .breadcrumb { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; font-size: 1.2rem; color: var(--neutral); }
    .breadcrumb a { color: var(--neutral); font-weight: 500; text-decoration: underline; }
    .bc-sep { width: 12px; height: 12px; fill: var(--neutral); }

    /* ══ BOTTOM CTA FIXE ═════════════════════════ */
    .bottom-cta {
        position: fixed; bottom: 0; left: 50%; transform: translateX(-50%);
        width: 100%; max-width: 430px; z-index: 100;
        background: var(--surface); border-top: 1px solid var(--outline);
        padding: 12px 16px calc(12px + env(safe-area-inset-bottom));
        display: flex; gap: 8px;
    }
    .btn-contacter { flex: 1; height: 52px; border-radius: 99px; border: 1.5px solid var(--support); color: var(--support); background: transparent; font-size: 1.5rem; font-weight: 900; display: flex; align-items: center; justify-content: center; transition: background .15s; }
    .btn-contacter:active { background: var(--support-bg); }
    .btn-reserver { flex: 1; height: 52px; border-radius: 99px; background: var(--main); color: #fff; font-size: 1.5rem; font-weight: 900; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background .15s; box-shadow: 0 2px 8px rgba(236,90,19,.25); }
    .btn-reserver:active { background: var(--main-hover); }
    .btn-reserver svg { width: 16px; height: 16px; fill: #fff; }

    .spacer { height: 92px; }

    /* ══ LIGHTBOX ════════════════════════════════ */
    .lightbox { display: none; position: fixed; inset: 0; z-index: 999; background: rgba(0,0,0,.97); flex-direction: column; }
    .lightbox.open { display: flex; }
    .lb-head { display: flex; align-items: center; justify-content: space-between; padding: 16px; }
    .lb-count { color: #fff; font-size: 1.4rem; font-weight: 700; }
    .lb-close { width: 44px; height: 44px; border-radius: 8px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; }
    .lb-close svg { width: 20px; height: 20px; fill: #fff; }
    .lb-body { flex: 1; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .lb-img { max-width: 96vw; max-height: 75vh; object-fit: contain; }
    .lb-nav { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 8px; background: rgba(255,255,255,.15); display: flex; align-items: center; justify-content: center; }
    .lb-nav svg { width: 20px; height: 20px; fill: #fff; }
    .lb-prev { left: 8px; } .lb-next { right: 8px; }
    .lb-thumbs { display: flex; gap: 6px; justify-content: center; padding: 16px; overflow-x: auto; }
    .lb-th { width: 54px; height: 40px; border-radius: 5px; object-fit: cover; opacity: .45; border: 2px solid transparent; flex-shrink: 0; cursor: pointer; }
    .lb-th.active { opacity: 1; border-color: #fff; }
    </style>
</head>

<body>

{{-- ── LIGHTBOX ──────────────────────────────── --}}
<div class="lightbox" id="lightbox">
    <div class="lb-head">
        <span class="lb-count" id="lbCount">1 / {{ $ad->photos->count() }}</span>
        <button class="lb-close" onclick="closeLB()">
            <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke="#fff" stroke-width="2.5" fill="none"/></svg>
        </button>
    </div>
    <div class="lb-body">
        <button class="lb-nav lb-prev" onclick="lbSlide(-1)">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" d="m16.7,2.28c.38.38.4,1.02.03,1.41l-7.69,8.31,7.69,8.31c.37.4.36,1.03-.03,1.41-.38.38-.99.37-1.36-.03l-7.87-8.51c-.15-.16-.27-.34-.35-.54-.08-.21-.12-.43-.12-.65s.04-.44.12-.65c.08-.2.2-.38.35-.54L15.34,2.31c.37-.4.98-.41,1.36-.03Z"/></svg>
        </button>
        <img src="" alt="" class="lb-img" id="lbImg"/>
        <button class="lb-nav lb-next" onclick="lbSlide(1)">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" d="m7.3,2.28c-.38.38-.4,1.02-.03,1.41l7.69,8.31-7.69,8.31c-.37.4-.36,1.03.03,1.41.38.38.99.37,1.36-.03l7.87-8.51c.15-.16.27-.34.35-.54.08-.21.12-.43.12-.65s-.04-.44-.12-.65c-.08-.2-.2-.38-.35-.54L8.66,2.31c-.37-.4-.98-.41-1.36-.03Z"/></svg>
        </button>
    </div>
    <div class="lb-thumbs">
        @foreach($ad->photos as $i => $p)
            <img src="{{ $p->url }}" alt="" class="lb-th{{ $i===0?' active':'' }}" onclick="lbGo({{ $i }})"/>
        @endforeach
    </div>
</div>

{{-- ── STICKY BAR ────────────────────────────── --}}
<div class="sticky-bar" id="stickyBar">
    <div class="sticky-inner">
        @if($ad->photos->isNotEmpty())
        <img src="{{ $ad->photos->first()->url }}" alt="" class="sticky-thumb"/>
        @endif
        <div class="sticky-info">
            <div class="sticky-title">{{ $ad->title }}</div>
            <div class="sticky-price">{{ $ad->formatted_price }}</div>
        </div>
        <div class="sticky-fav">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" d="M7.61047 3C4.47262 3 2 5.7967 2 9.15721C2 11.9142 3.39876 13.8984 3.97217 14.6611C5.85936 17.1731 8.40205 18.8367 10.7565 20.377C11.0112 20.5436 11.2637 20.7089 11.513 20.8735C11.7618 21.0379 12.0775 21.0424 12.3304 20.885C12.546 20.7508 12.7639 20.6162 12.9834 20.4807C15.4422 18.9622 18.1038 17.3184 20.0514 14.6945C20.6977 13.8253 21.9997 11.8564 22 9.15476C22.0034 5.7964 19.53 3 16.3925 3C14.6028 3 13.0234 3.91646 12.0008 5.32504C10.9794 3.9162 9.39988 3 7.61047 3Z"/></svg>
        </div>
    </div>
</div>

{{-- ── GALERIE ───────────────────────────────── --}}
<div class="carousel-wrap">
    <a href="/" class="btn-back" aria-label="Retour">
        <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.53868 6.2764C8.9371 6.65583 8.95002 7.28383 8.56754 7.67908L5.34622 11.0079H21C21.5523 11.0079 22 11.4521 22 12C22 12.5479 21.5523 12.9921 21 12.9921H5.34622L8.56754 16.3209C8.95002 16.7162 8.9371 17.3442 8.53868 17.7236C8.14027 18.103 7.50724 18.0902 7.12477 17.695L2.27861 12.687C1.90713 12.3031 1.90713 11.6969 2.27861 11.313L7.12477 6.30503C7.50724 5.90978 8.14027 5.89696 8.53868 6.2764Z"/></svg>
    </a>
    <div class="gallery-actions">
        <button class="btn-action" onclick="shareAd()" aria-label="Partager">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.4613 6.16913L7.5446 9.07413C7.01557 8.79113 6.41114 8.63067 5.76923 8.63067C3.68754 8.63067 2 10.3182 2 12.3999C2 14.4816 3.68754 16.1691 5.76923 16.1691C6.41112 16.1691 7.01554 16.0087 7.54456 15.7257L14.4615 18.5989C14.4614 18.6095 14.4613 18.6201 14.4613 18.6307C14.4613 20.7124 16.1489 22.3999 18.2306 22.3999C20.3123 22.3999 21.9998 20.7124 21.9998 18.6307C21.9998 16.549 20.3123 14.8614 18.2306 14.8614C16.8699 14.8614 15.6777 15.5824 15.015 16.6631L9.08232 14.1988C9.3732 13.6642 9.53846 13.0513 9.53846 12.3999C9.53846 11.7485 9.3732 11.1357 9.08233 10.6011L15.015 8.13671C15.6777 9.21743 16.87 9.93836 18.2306 9.93836C20.3123 9.93836 21.9998 8.25082 21.9998 6.16913C21.9998 4.08744 20.3123 2.3999 18.2306 2.3999C16.1489 2.3999 14.4613 4.08744 14.4613 6.16913Z"/></svg>
        </button>
        <a href="{{ route('ads.favorites') }}" class="btn-action" aria-label="Mes favoris" style="text-decoration:none;">
            <svg viewBox="0 0 24 24" style="fill:none;stroke:var(--on-surface);stroke-width:2;"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        </a>
        <button class="btn-action {{ $isLiked ? 'fav-red' : '' }}" id="favBtn" onclick="toggleFav()" aria-label="Favoris" aria-pressed="{{ $isLiked ? 'true' : 'false' }}">
            <span id="favCount">{{ $likesTotal }}</span>
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" d="M7.61047 3C4.47262 3 2 5.7967 2 9.15721C2 11.9142 3.39876 13.8984 3.97217 14.6611C5.85936 17.1731 8.40205 18.8367 10.7565 20.377C11.0112 20.5436 11.2637 20.7089 11.513 20.8735C11.7618 21.0379 12.0775 21.0424 12.3304 20.885C12.546 20.7508 12.7639 20.6162 12.9834 20.4807C15.4422 18.9622 18.1038 17.3184 20.0514 14.6945C20.6977 13.8253 21.9997 11.8564 22 9.15476C22.0034 5.7964 19.53 3 16.3925 3C14.6028 3 13.0234 3.91646 12.0008 5.32504C10.9794 3.9162 9.39988 3 7.61047 3Z"/></svg>
        </button>
    </div>
    <div class="carousel-track" id="cTrack">
        @foreach($ad->photos as $i => $p)
        <div class="carousel-slide">
            <img src="{{ $p->url }}" alt="{{ $ad->title }} ({{ $i+1 }})" loading="{{ $i===0?'eager':'lazy' }}" fetchpriority="{{ $i===0?'high':'low' }}" onclick="openLB({{ $i }})"/>
        </div>
        @endforeach
    </div>
    <div class="carousel-footer">
        <a href="#" class="btn-similar" rel="nofollow">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M5 2C3.34 2 2 3.34 2 5V7C2 7.55 1.55 8 1 8C0.45 8 0 7.55 0 7V5C0 2.24 2.24 0 5 0H7C7.55 0 8 0.45 8 1C8 1.55 7.55 2 7 2H5Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M5 18C3.34 18 2 16.66 2 15V13C2 12.45 1.55 12 1 12C0.45 12 0 12.45 0 13V15C0 17.76 2.24 20 5 20H7C7.55 20 8 19.55 8 19C8 18.45 7.55 18 7 18H5Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M15 2C16.66 2 18 3.34 18 5V7C18 7.55 18.45 8 19 8C19.55 8 20 7.55 20 7V5C20 2.24 17.76 0 15 0H13C12.45 0 12 0.45 12 1C12 1.55 12.45 2 13 2H15Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M15 18C16.66 18 18 16.66 18 15V13C18 12.45 18.45 12 19 12C19.55 12 20 12.45 20 13V15C20 17.76 17.76 20 15 20H13C12.45 20 12 19.55 12 19C12 18.45 12.45 18 13 18H15Z"></path><path d="M15.28 13.86L13.68 12.25C14.2 11.46 14.5 10.52 14.5 9.5C14.5 6.74 12.26 4.5 9.5 4.5C6.74 4.5 4.5 6.74 4.5 9.5C4.5 12.26 6.74 14.5 9.5 14.5C10.52 14.5 11.46 14.2 12.25 13.68L13.78 15.21C14.26 15.6 14.84 15.58 15.21 15.21C15.58 14.84 15.6 14.26 15.28 13.86ZM6.51 9.51C6.51 7.85 7.85 6.51 9.51 6.51C11.17 6.51 12.51 7.85 12.51 9.51C12.51 11.17 11.17 12.51 9.51 12.51C7.85 12.51 6.51 11.17 6.51 9.51Z"></path></svg>
            Annonces similaires
        </a>
        <span class="badge-counter">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0004 8.54376C9.72753 8.54376 7.88501 10.4042 7.88501 12.6991C7.88501 14.994 9.72753 16.8544 12.0004 16.8544C14.2733 16.8544 16.1158 14.994 16.1158 12.6991C16.1158 10.4042 14.2733 8.54376 12.0004 8.54376Z"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M9.23077 4C8.91601 4 8.61962 4.14963 8.43077 4.40388L6.65385 6.79612H4.38462C3.75218 6.79612 3.14564 7.04979 2.69844 7.50134C2.25124 7.95288 2 8.5653 2 9.20388V17.5922C2 18.2308 2.25124 18.8432 2.69844 19.2948C3.14564 19.7463 3.75218 20 4.38462 20H19.6154C20.2478 20 20.8544 19.7463 21.3016 19.2948C21.7488 18.8432 22 18.2308 22 17.5922V9.20388C22 8.5653 21.7488 7.95288 21.3016 7.50134C20.8544 7.04979 20.2478 6.79612 19.6154 6.79612H17.3462L15.5692 4.40388C15.3804 4.14963 15.084 4 14.7692 4H9.23077Z"></path></svg>
            <span id="slideNum">1</span><span class="light">/{{ $ad->photos->count() }}</span>
        </span>
    </div>
    @if($ad->photos->count() > 1)
    <div class="carousel-dots" id="dots">
        @foreach($ad->photos as $i => $p)
            <div class="dot{{ $i===0?' active':'' }}"></div>
        @endforeach
    </div>
    @endif
</div>

{{-- ── TITLE CARD FLOTTANTE (CORRIGÉ) ─────────── --}}
<div class="title-card">
    <h1 class="ad-title">{{ $ad->title }}</h1>
    <p class="ad-meta">
        <a href="#">{{ $ad->city }}</a>
        @if($ad->vehicle)
            <span class="sep">·</span>
            <span>{{ $ad->vehicle->year }} · {{ number_format($ad->vehicle->mileage,0,',',' ') }} km · {{ ucfirst($ad->vehicle->fuel_type ?? '') }}</span>
        @endif
    </p>
    <p class="ad-price">{{ $ad->formatted_price }}</p>
    <p class="ad-date">{{ $ad->published_at?->format('d/m/Y') }} à {{ $ad->published_at?->format('H:i') }}</p>
    <div>
        <span class="tag-secure">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.2442 2.18401C12.5465 1.93866 11.7867 1.93866 11.0891 2.18402L5.16923 4.26603C3.86633 4.72426 2.97006 5.96575 3.00077 7.37527C3.06992 10.5501 3.43134 13.4491 5.2821 16.4484C6.78059 18.8768 8.91345 20.7612 11.4111 21.844C11.8974 22.0514 12.4358 22.0515 12.9183 21.8456C15.422 20.7603 17.5563 18.8735 19.055 16.4422C20.9052 13.4405 21.2639 10.5486 21.3326 7.37544C21.3631 5.96594 20.4669 4.72425 19.1638 4.26595L13.2442 2.18401Z"/></svg>
            Paiement sécurisé : 19,99&nbsp;€
        </span>
    </div>
</div>

{{-- ── BOUTON RÉSERVER — HORS CARTE (CORRIGÉ) ──── --}}
<div class="reserve-section">
    <a href="{{ route('ads.reserve', $ad->id) }}" class="btn-reserve" id="p2pvo_adview_cta_main">
        <svg viewBox="0 0 24 24"><path d="M19.1054 4.23499L13.2885 2.18799C12.5655 1.93734 11.7844 1.93734 11.0615 2.18799L5.24453 4.23499C3.89833 4.70287 2.96762 5.98956 3.00086 7.4517C3.06734 10.5849 3.42467 13.4674 5.26946 16.4585C6.75693 18.8731 8.88426 20.753 11.3689 21.8308C11.8841 22.0564 12.4575 22.0564 12.9727 21.8308C15.4657 20.753 17.5847 18.8731 19.0722 16.4501C20.917 13.459 21.266 10.5849 21.3325 7.4517C21.3657 5.98956 20.435 4.70287 19.0888 4.23499H19.1054ZM12.7068 3.85901L18.5237 5.906C19.1802 6.13995 19.604 6.74987 19.5874 7.41828C19.5209 10.4261 19.1885 12.9326 17.5847 15.5227C16.2718 17.6533 14.427 19.2742 12.283 20.2099C12.2082 20.2433 12.1251 20.2433 12.0503 20.2099C9.91469 19.2825 8.06158 17.6533 6.74862 15.5311C5.15312 12.941 4.81242 10.4261 4.74594 7.41828C4.72932 6.74987 5.16143 6.13995 5.8096 5.906L11.6265 3.85901C11.9755 3.73368 12.3495 3.73368 12.6902 3.85901H12.7068Z"/><path d="M8.0782 12.2559C7.85384 12.5817 7.85384 12.7906 7.85384 12.9995C7.85384 12.9995 7.92863 13.1916 8.0782 13.3337C8.22778 13.4757 8.4106 13.5426 8.61835 13.5426H9.2499C9.38286 14.1107 9.58229 14.612 9.84821 15.0548C10.1972 15.6146 10.646 16.0407 11.1861 16.3332C11.7262 16.6256 12.3329 16.7676 13.006 16.7676C13.4381 16.7676 13.8702 16.7008 14.3023 16.5587C14.751 16.4084 15.1333 16.1828 15.4574 15.882L14.5017 14.5702C14.2774 14.7206 14.053 14.8292 13.8286 14.8961C13.6126 14.9546 13.3799 14.9796 13.114 14.9796C12.7899 14.9796 12.5074 14.9128 12.2498 14.7875C12.0005 14.6538 11.7761 14.4449 11.5933 14.1525C11.4936 13.9854 11.4022 13.7849 11.3274 13.5509H12.5822C12.7899 13.5509 12.9727 13.4757 13.114 13.3253C13.2553 13.1833 13.3301 12.9995 13.3301 12.799C13.3301 12.5984 13.2553 12.4146 13.114 12.2726C12.9727 12.1222 12.7899 12.047 12.5822 12.047H11.0781C11.0781 11.9384 11.0781 11.8298 11.0781 11.7211C11.0781 11.6376 11.0781 11.554 11.0781 11.4705H12.5822C12.7899 11.4705 12.9727 11.3953 13.114 11.2449C13.2553 11.1029 13.3301 10.9191 13.3301 10.7185C13.3301 10.518 13.2553 10.3342 13.114 10.1922C12.9727 10.0418 12.7899 9.96658 12.5822 9.96658H11.3274C11.4022 9.74099 11.4936 9.54047 11.5933 9.38172C11.7761 9.08929 11.9922 8.88877 12.2415 8.76345C12.4991 8.62141 12.7899 8.55457 13.1057 8.55457C13.355 8.55457 13.6043 8.58799 13.8453 8.65483C14.0863 8.72167 14.2857 8.82193 14.4685 8.96397C14.6347 9.08929 14.8092 9.17285 14.992 9.17285H15.0003C15.1748 9.16449 15.3244 9.106 15.4491 8.99739C15.5737 8.88877 15.6485 8.74674 15.6984 8.58799C15.7482 8.42089 15.7565 8.25379 15.715 8.08668C15.6734 7.90287 15.5654 7.74412 15.4075 7.61044C15.0585 7.30966 14.6763 7.09243 14.2441 6.95875C13.8286 6.82507 13.4132 6.76658 12.9893 6.76658C12.3162 6.76658 11.7096 6.90862 11.1695 7.20104C10.6293 7.49347 10.1806 7.91958 9.83159 8.47937C9.56567 8.91384 9.36624 9.40679 9.23328 9.98329H8.60173C8.39398 9.98329 8.21116 10.0585 8.06158 10.2005C7.91201 10.3426 7.83722 10.5264 7.83722 10.7352C7.83722 10.9441 7.91201 11.1363 8.06158 11.2783C8.21116 11.4204 8.39398 11.4872 8.60173 11.4872H9.04215C9.04215 11.5708 9.04215 11.6627 9.04215 11.7462C9.04215 11.8465 9.04215 11.9551 9.04215 12.0637H8.60173C8.39398 12.0637 8.21116 12.1389 8.06158 12.2809L8.0782 12.2559Z"/></svg>
        Réserver
    </a>
</div>
<div class="cta-divider"></div>

{{-- ── PAIEMENT SÉCURISÉ ────────────────────── --}}
<div class="section-pay" style="border-bottom: 1px solid var(--outline);">
    <h2 class="section-h2">Paiement sécurisé</h2>
    <p class="pay-text">Achetez ce véhicule en toute confiance grâce au Paiement sécurisé. Bénéficiez de :</p>
    <ul class="pay-list">
        <li class="pay-item">
            <svg viewBox="0 0 24 24"><path d="M17.5928 22H6.40715C5.07733 22 4 20.9333 4 19.6167V11.3083C4 10.1 4.91741 9.09167 6.09574 8.94167V7.84167C6.09574 6.28333 6.71015 4.80833 7.82115 3.70833C8.94056 2.60833 10.4219 2 12.0042 2C13.5865 2 15.0594 2.60833 16.1789 3.70833C17.2983 4.81667 17.9043 6.28333 17.9043 7.84167V8.94167C19.0826 9.09167 20 10.0917 20 11.3083V19.6167C20 20.9333 18.9227 22 17.5928 22Z"/></svg>
            <p><b>La réservation</b> pour vous assurer que le véhicule ne vous passe pas sous le nez.</p>
        </li>
        <li class="pay-item">
            <svg viewBox="0 0 24 24"><path d="M19.1054 4.23499L13.2885 2.18799C12.5655 1.93734 11.7844 1.93734 11.0615 2.18799L5.24453 4.23499C3.89833 4.70287 2.96762 5.98956 3.00086 7.4517C3.06734 10.5849 3.42467 13.4674 5.26946 16.4585C6.75693 18.8731 8.88426 20.753 11.3689 21.8308C11.8841 22.0564 12.4575 22.0564 12.9727 21.8308C15.4657 20.753 17.5847 18.8731 19.0722 16.4501C20.917 13.459 21.266 10.5849 21.3325 7.4517C21.3657 5.98956 20.435 4.70287 19.0888 4.23499H19.1054Z"/></svg>
            <p><b>Le Paiement sécurisé</b> pour protéger votre argent sur un compte séquestre jusqu'au jour de la vente.</p>
        </li>
    </ul>
    <a href="https://assistance.leboncoin.info/hc/fr/articles/360011044960" class="link-more">En savoir plus</a>
</div>

{{-- ── PUB SLOT ─────────────────────────────── --}}
<div class="ad-pub-slot">
    <div class="ad-pub-placeholder">
        <span class="pub-logo">leboncoin</span>
        <span class="pub-label">Publicité</span>
    </div>
</div>

{{-- ── INFORMATIONS CLÉS ────────────────────── --}}
@if($ad->vehicle)
<div class="section">
    <h2 class="section-h2">Les informations clés</h2>
    <div class="criteria-list">
        @php
        $svgMarque  = '<path fill-rule="evenodd" clip-rule="evenodd" d="M3 4h18v2H3zm0 7h18v2H3zm0 7h18v2H3z"/>';
        $svgModele  = '<path fill-rule="evenodd" clip-rule="evenodd" d="M3 4h18v2H3zm0 7h18v2H3zm0 7h18v2H3z"/>';
        $svgAnnee   = '<path fill-rule="evenodd" clip-rule="evenodd" d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13zM7 10h5v5H7z"/>';
        $svgKm      = '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/>';
        $svgEnergie = '<path fill-rule="evenodd" clip-rule="evenodd" d="M17 5H7c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 14H7V7h10v12zm-5-4c-1.1 0-2-.9-2-2V9h4v4c0 1.1-.9 2-2 2z"/>';
        $svgBoite   = '<path fill-rule="evenodd" clip-rule="evenodd" d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/>';
        $svgPortes  = '<path fill-rule="evenodd" clip-rule="evenodd" d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H6V4h12v16zm-7-8c.55 0 1-.45 1-1s-.45-1-1-1-1 .45-1 1 .45 1 1 1z"/>';
        $svgPlaces  = '<path fill-rule="evenodd" clip-rule="evenodd" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>';
        $rows = array_filter([
            ['Marque',             $svgMarque,  $ad->vehicle->brand,       true],
            ['Modèle',             $svgModele,  $ad->vehicle->model,       true],
            ['Année modèle',       $svgAnnee,   $ad->vehicle->year,        false],
            ['Kilométrage',        $svgKm,      $ad->vehicle->mileage ? number_format($ad->vehicle->mileage,0,',',' ').' km' : null, false],
            ['Énergie',            $svgEnergie, ucfirst($ad->vehicle->fuel_type ?? ''), false],
            ['Boîte de vitesse',   $svgBoite,   ucfirst($ad->vehicle->gearbox ?? ''), false],
            ['Nombre de portes',   $svgPortes,  $ad->vehicle->doors ?: null, false],
            ['Nombre de place(s)', $svgPlaces,  $ad->vehicle->seats ?: null, false],
        ], fn($r) => !empty($r[2]));
        @endphp
        @foreach($rows as $r)
        <div class="criteria-row">
            <div class="criteria-icon"><svg viewBox="0 0 24 24">{!! $r[1] !!}</svg></div>
            <div class="criteria-left"><span class="criteria-lbl">{{ $r[0] }}</span></div>
            <div class="criteria-val">
                @if($r[3])<a href="#" rel="noopener">{{ $r[2] }}</a>@else{{ $r[2] }}@endif
            </div>
        </div>
        @endforeach
    </div>
    <a href="#" class="btn-more-criteria">Voir les 11 critères supplémentaires</a>
</div>
@endif

{{-- ── DESCRIPTION ──────────────────────────── --}}
@if($ad->description)
<div class="section" style="border-top:none;">
    <h2 class="section-h2">Description</h2>
    <p class="desc-text" id="descShort">{{ $ad->description }}</p>
    <p class="desc-full" id="descFull">{{ $ad->description }}</p>
    <button class="btn-desc-more" id="descBtn" onclick="toggleDesc()" aria-expanded="false">Voir plus</button>
</div>
@endif

{{-- ── FINANCEMENT ──────────────────────────── --}}
<div class="section">
    <h2 class="section-h2">Financement</h2>
    <div>
        <span class="tag-sponsored">Sponsorisé</span>
        <p class="fin-disclaimer">Un crédit vous engage et doit être remboursé. Vérifiez vos capacités de remboursement avant de vous engager.</p>
        <p class="fin-subtitle">Simuler un financement</p>
        <div class="fin-cetelem-row">
            <span class="fin-avec">avec</span>
            <span class="fin-cetelem">cetelem</span>
        </div>
        <div class="fin-input-wrap">
            <label class="fin-input-label" for="finAmt">Montant du financement</label>
            <div class="fin-input-row">
                <input type="number" class="fin-input" id="finAmt" placeholder="Montant du financement" name="amount"/>
                <svg class="fin-euro" viewBox="0 0 24 24"><path d="M16.7701 4.00002C17.4679 3.99829 18.1612 4.11655 18.8206 4.34988C19.3322 4.53088 19.8907 4.25463 20.0682 3.73285C20.2456 3.21106 19.9748 2.64134 19.4632 2.46034C18.5875 2.15046 17.6664 1.99484 16.7394 2.00013C14.2003 2.11846 11.848 3.24045 10.1573 5.12182C9.21218 6.17345 8.51677 7.41452 8.10508 8.75003H4.98039C4.43893 8.75003 4 9.19775 4 9.75003C4 10.3023 4.43893 10.75 4.98039 10.75H7.71008C7.67232 11.1633 7.66056 11.5808 7.67565 12C7.66056 12.4193 7.67232 12.8368 7.71008 13.2501H4.98039C4.43893 13.2501 4 13.6978 4 14.2501C4 14.8023 4.43893 15.2501 4.98039 15.2501H8.10508C8.51677 16.5856 9.21218 17.8266 10.1573 18.8782C11.848 20.7596 14.2003 21.8816 16.6998 21.9989C17.8878 22.0039 19.0226 21.7667 20.0748 21.303C20.5719 21.0839 20.8008 20.4954 20.586 19.9884C20.3713 19.4814 19.7943 19.2479 19.2972 19.467C18.4989 19.8188 17.6385 20.0001 16.769 20C14.7951 19.9017 12.9385 19.0132 11.6027 17.5268C10.9969 16.8528 10.5205 16.0806 10.1878 15.2501H13.8038C14.3452 15.2501 14.7842 14.8023 14.7842 14.2501C14.7842 13.6978 14.3452 13.2501 13.8038 13.2501H9.68159C9.63553 12.8513 9.62003 12.4471 9.63634 12.041C9.63743 12.0137 9.63743 11.9864 9.63634 11.9591C9.62003 11.5529 9.63553 11.1487 9.68159 10.75H13.8038C14.3452 10.75 14.7842 10.3023 14.7842 9.75003C14.7842 9.19775 14.3452 8.75003 13.8038 8.75003H10.1878C10.5205 7.91948 10.9969 7.1473 11.6027 6.47327C12.9387 4.98662 14.7957 4.09801 16.7701 4.00002Z"/></svg>
            </div>
        </div>
        <div class="fin-duration-row">
            <p class="fin-dur-label">Durée du financement</p>
            <p class="fin-dur-val" id="finVal">24 mois</p>
        </div>
        <input type="range" class="fin-slider" id="finSlider" min="6" max="84" step="6" value="24"/>
        <div class="fin-limits"><span>6 mois</span><span>84 mois</span></div>
        <button class="btn-simulate" type="button">Simuler ma mensualité</button>
        <p class="fin-legal">Cetelem est une marque de BNP Paribas Personal Finance, Société Anonyme au capital de : 634 574 115 €. Siège social : 1 Boulevard Haussmann - 75009 Paris France. RCS : Paris n° 542 097 902. N° ORIAS : 07 023 128 (www.orias.fr).</p>
    </div>
</div>

{{-- ── LOCALISATION ─────────────────────────── --}}
<div class="section" id="mapSection">
    <h2 class="section-h2">Localisation</h2>
    <p class="loc-city">{{ $ad->city }}<span>({{ $ad->vehicle?->zipcode ?? '68460' }})</span></p>
    <div class="map-box" onclick="this.querySelector('iframe') && this.querySelector('iframe').style.setProperty('pointer-events','all')">
        <div id="leaflet-map" style="width:100%;height:100%;background:linear-gradient(135deg,#c8e6c9 0%,#a5d6a7 50%,#81c784 100%);display:flex;align-items:center;justify-content:center;">
            <svg width="32" height="32" viewBox="0 0 24 24" style="fill:#2e7d32;opacity:.6;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
        </div>
    </div>
</div>

{{-- ── VENDEUR ──────────────────────────────── --}}
<div class="section" id="contactSection">
    <h2 class="section-h2">Vendu par</h2>

    <div class="seller-top">
        <div class="seller-ava">
            @if(isset($ad->seller->profile_picture_url) && $ad->seller->profile_picture_url)
                <img src="{{ $ad->seller->profile_picture_url }}" alt="{{ $ad->seller->full_name ?? '' }}"/>
            @else
                <span class="seller-ava-initial">
                    {{ mb_strtoupper(mb_substr($ad->seller->first_name ?? $ad->seller->full_name ?? 'V', 0, 1)) }}
                </span>
            @endif
        </div>
        <button class="btn-follow" type="button" aria-label="Suivre l'actualité de ce vendeur">Suivre</button>
    </div>

    <span class="seller-name">{{ $ad->seller->full_name ?? 'younes687' }}</span>

    <ul class="seller-info-list">
        <li class="seller-info-item">
            <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.74332 3.11303C8.74332 2.49832 8.22298 2 7.58111 2C6.93923 2 6.41889 2.49832 6.41889 3.11303V3.69276H5.81355C3.70738 3.69276 2 5.32789 2 7.34492V18.3478C2 20.362 3.69905 22 5.80825 22H18.191C20.2969 22 22 20.3699 22 18.3527V7.34016C22 5.3204 20.2898 3.69276 18.1865 3.69276H17.5811V3.11303C17.5811 2.49832 17.0608 2 16.4189 2C15.777 2 15.2567 2.49832 15.2567 3.11303V3.69276H8.74332V3.11303Z"/></svg>
            <span>Membre depuis {{ $ad->seller->member_since ?? 'avril 2021' }}</span>
        </li>
        <li class="seller-info-item">
            <svg viewBox="0 0 24 24"><path d="M17.5418 3.68803C19.1923 4.78901 20.4785 6.35406 21.2378 8.1851V8.21847C21.9959 10.0478 22.1941 12.0604 21.8072 14.0021C21.4203 15.9438 20.4657 17.7275 19.0641 19.1279C17.6611 20.5043 15.8839 21.4386 13.9534 21.8146C12.0229 22.1907 10.0242 21.9919 8.20597 21.243Z"/><path d="M15.1595 15.3885L11.531 13.5855C11.3641 13.5065 11.2224 13.3827 11.122 13.2279C11.0216 13.0732 10.9664 12.8935 10.9625 12.7091V7.15009C10.9499 7.01482 10.9657 6.87842 11.009 6.7496C11.0522 6.62079 11.1219 6.5024 11.2135 6.402C11.3052 6.3016 11.4169 6.22141 11.5413 6.16653C11.6658 6.11166 11.8004 6.08329 11.9365 6.08329C12.0725 6.08329 12.2071 6.11166 12.3316 6.16653C12.4561 6.22141 12.5677 6.3016 12.6594 6.402C12.7511 6.5024 12.8207 6.62079 12.864 6.7496C12.9072 6.87842 12.923 7.01482 12.9105 7.15009V12.1082L15.9955 13.6523C16.2002 13.7777 16.351 13.9744 16.4188 14.2043C16.4865 14.4343 16.4665 14.6811 16.3624 14.8971C16.2584 15.1131 16.0778 15.283 15.8556 15.3738C15.6334 15.4645 15.3853 15.4698 15.1595 15.3885Z"/></svg>
            <span>Dernière activité {{ $ad->seller->last_activity ?? 'il y a 4 jours' }}</span>
        </li>
    </ul>

    <div class="seller-badges">
        <button type="button" class="badge-btn">
            <div class="badge-circle">
                <svg viewBox="0 0 24 24"><path d="M21.7422 9.79C21.4124 8.33 20.7426 6.94 19.813 5.77C18.8834 4.6 17.6838 3.64 16.3344 2.99C14.9949 2.34 13.4955 2 11.9961 2C10.1868 2 8.40749 2.49 6.8481 3.42C5.29871 4.35 4.02921 5.69 3.17954 7.28C2.33988 8.88 1.93004 10.67 2.02 12.48C2.09997 14.11 2.57978 15.7 3.38946 17.11L2.05999 20.66C1.94003 20.99 2.00001 21.37 2.22992 21.65C2.45983 21.92 2.82968 22.05 3.17954 21.99L7.91768 21.13C9.18718 21.69 10.5766 22 11.9561 22H12.0161C13.4955 22 14.9849 21.67 16.3144 21.03C17.6538 20.38 18.8634 19.43 19.803 18.26C20.7426 17.09 21.4124 15.71 21.7522 14.25C22.0821 12.79 22.0821 11.25 21.7522 9.79H21.7422Z"/><path d="M15.7646 8.5C15.4347 8.5 15.1148 8.63 14.8849 8.85L10.7366 12.77L9.10721 11.23C8.8773 11.01 8.55743 10.88 8.22756 10.88C7.89769 10.88 7.57781 11.01 7.3479 11.23C6.88808 11.67 6.88808 12.35 7.3479 12.78L9.85692 15.16C10.0868 15.38 10.4067 15.51 10.7366 15.51C11.0664 15.51 11.3863 15.38 11.6162 15.16L16.6442 10.41C17.1041 9.97 17.1041 9.29 16.6442 8.86C16.4143 8.64 16.0945 8.51 15.7646 8.51V8.5Z" fill="#89380f"/></svg>
            </div>
            <span class="badge-lbl">Réactif</span>
        </button>
        <button type="button" class="badge-btn">
            <div class="badge-circle">
                <svg viewBox="0 0 24 24"><path d="M9.95972 19.8585H6.69527C6.37719 19.8585 6.12608 19.6003 6.12608 19.2921V18.7257H9.95135C10.5373 18.7257 11.0144 18.2509 11.0144 17.6678C11.0144 17.0848 10.5373 16.61 9.95135 16.61H6.12608V7.35589H15.4256V14.4193C15.4256 15.0024 15.9027 15.4772 16.4886 15.4772C17.0745 15.4772 17.5517 15.0024 17.5517 14.4193V4.68211C17.5517 3.19945 16.3463 2 14.8564 2H6.69527C5.20534 2 4 3.19945 4 4.68211V19.2921C4 20.7748 5.20534 21.9742 6.69527 21.9742H9.95972C10.5456 21.9742 11.0228 21.4994 11.0228 20.9164C11.0228 20.3333 10.5456 19.8585 9.95972 19.8585ZM15.4256 5.24852H6.12608V4.68211C6.12608 4.36559 6.38556 4.1157 6.69527 4.1157H14.8564C15.1745 4.1157 15.4256 4.37392 15.4256 4.68211V5.24852Z"/></svg>
            </div>
            <span class="badge-lbl">Numéro vérifié</span>
        </button>
    </div>
</div>

{{-- ── SIGNALEMENT / DROITS ─────────────────── --}}
<div class="footer-links">
    <button class="footer-link" type="button">
        <svg viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.97961 2H18.187C18.5696 2 18.9172 2.22734 19.077 2.58214C19.2369 2.93694 19.1798 3.35428 18.9308 3.65079L15.4081 7.84615L18.9308 12.0415C19.1798 12.338 19.2369 12.7554 19.077 13.1102C18.9172 13.465 18.5696 13.6923 18.187 13.6923H6.95922V21C6.95922 21.5523 6.52063 22 5.97961 22C5.43859 22 5 21.5523 5 21V3C5 2.44772 5.43859 2 5.97961 2Z"/></svg>
        Signaler l'annonce
    </button>
    <a href="/dc/vos_droits_et_obligations" class="footer-link" rel="nofollow">
        <svg viewBox="0 0 24 24"><path d="M11.9999 16.8209C12.5673 16.8209 13.0267 16.3609 13.0267 15.7943L13.0267 11.1539C13.0267 10.5872 12.5673 10.1273 11.9999 10.1273C11.4324 10.1273 10.973 10.5872 10.973 11.1539L10.973 15.7943C10.973 16.361 11.4324 16.8209 11.9999 16.8209Z"/><path d="M11.9999 9.15955C12.7809 9.15955 13.4135 8.52649 13.4135 7.74627C13.4135 6.96605 12.7809 6.333 11.9999 6.333C11.2189 6.333 10.5864 6.96605 10.5864 7.74627C10.5864 8.52649 11.2189 9.15955 11.9999 9.15955Z"/><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47684 2 2 6.47746 2 12C2 17.5225 6.47684 22 12 22C17.5232 22 22 17.5225 22 12C22 6.47746 17.5232 2 12 2Z"/></svg>
        Vos droits et obligations
    </a>
</div>

{{-- ── BREADCRUMB ───────────────────────────── --}}
<div class="breadcrumb-wrap">
    <nav class="breadcrumb" aria-label="Fil d'Ariane">
        <a href="/">Accueil</a>
        <svg class="bc-sep" viewBox="0 0 24 24"><path fill-rule="evenodd" d="m7.3,2.28c-.38.38-.4,1.02-.03,1.41l7.69,8.31-7.69,8.31c-.37.4-.36,1.03.03,1.41.38.38.99.37,1.36-.03l7.87-8.51c.15-.16.27-.34.35-.54.08-.21.12-.43.12-.65s-.04-.44-.12-.65c-.08-.2-.2-.38-.35-.54L8.66,2.31c-.37-.4-.98-.41-1.36-.03Z"/></svg>
        <a href="#">Voitures</a>
        @if($ad->city)
        <svg class="bc-sep" viewBox="0 0 24 24"><path fill-rule="evenodd" d="m7.3,2.28c-.38.38-.4,1.02-.03,1.41l7.69,8.31-7.69,8.31c-.37.4-.36,1.03.03,1.41.38.38.99.37,1.36-.03l7.87-8.51c.15-.16.27-.34.35-.54.08-.21.12-.43.12-.65s-.04-.44-.12-.65c-.08-.2-.2-.38-.35-.54L8.66,2.31c-.37-.4-.98-.41-1.36-.03Z"/></svg>
        <a href="#">{{ $ad->city }}</a>
        @endif
        <svg class="bc-sep" viewBox="0 0 24 24"><path fill-rule="evenodd" d="m7.3,2.28c-.38.38-.4,1.02-.03,1.41l7.69,8.31-7.69,8.31c-.37.4-.36,1.03.03,1.41.38.38.99.37,1.36-.03l7.87-8.51c.15-.16.27-.34.35-.54.08-.21.12-.43.12-.65s-.04-.44-.12-.65c-.08-.2-.2-.38-.35-.54L8.66,2.31c-.37-.4-.98-.41-1.36-.03Z"/></svg>
        <span role="link" aria-current="page">{{ $ad->title }}</span>
    </nav>
</div>

<div class="spacer"></div>

{{-- ── BOTTOM CTA FIXE ─────────────────────── --}}
<div class="bottom-cta">
    <button class="btn-contacter" type="button" onclick="document.getElementById('contactSection').scrollIntoView({behavior:'smooth'})">Contacter</button>
    <a class="btn-reserver" href="{{ route('ads.reserve', $ad->id) }}" id="p2pvo_adview_cta_navbar">
        <svg viewBox="0 0 24 24"><path d="M19.1054 4.23499L13.2885 2.18799C12.5655 1.93734 11.7844 1.93734 11.0615 2.18799L5.24453 4.23499C3.89833 4.70287 2.96762 5.98956 3.00086 7.4517C3.06734 10.5849 3.42467 13.4674 5.26946 16.4585C6.75693 18.8731 8.88426 20.753 11.3689 21.8308C11.8841 22.0564 12.4575 22.0564 12.9727 21.8308C15.4657 20.753 17.5847 18.8731 19.0722 16.4501C20.917 13.459 21.266 10.5849 21.3325 7.4517C21.3657 5.98956 20.435 4.70287 19.0888 4.23499H19.1054ZM12.7068 3.85901L18.5237 5.906C19.1802 6.13995 19.604 6.74987 19.5874 7.41828C19.5209 10.4261 19.1885 12.9326 17.5847 15.5227C16.2718 17.6533 14.427 19.2742 12.283 20.2099C12.2082 20.2433 12.1251 20.2433 12.0503 20.2099C9.91469 19.2825 8.06158 17.6533 6.74862 15.5311C5.15312 12.941 4.81242 10.4261 4.74594 7.41828C4.72932 6.74987 5.16143 6.13995 5.8096 5.906L11.6265 3.85901C11.9755 3.73368 12.3495 3.73368 12.6902 3.85901H12.7068Z"/><path d="M8.0782 12.2559C7.85384 12.5817 7.85384 12.7906 7.85384 12.9995C7.85384 12.9995 7.92863 13.1916 8.0782 13.3337C8.22778 13.4757 8.4106 13.5426 8.61835 13.5426H9.2499C9.38286 14.1107 9.58229 14.612 9.84821 15.0548C10.1972 15.6146 10.646 16.0407 11.1861 16.3332C11.7262 16.6256 12.3329 16.7676 13.006 16.7676C13.4381 16.7676 13.8702 16.7008 14.3023 16.5587C14.751 16.4084 15.1333 16.1828 15.4574 15.882L14.5017 14.5702C14.2774 14.7206 14.053 14.8292 13.8286 14.8961C13.6126 14.9546 13.3799 14.9796 13.114 14.9796C12.7899 14.9796 12.5074 14.9128 12.2498 14.7875C12.0005 14.6538 11.7761 14.4449 11.5933 14.1525C11.4936 13.9854 11.4022 13.7849 11.3274 13.5509H12.5822C12.7899 13.5509 12.9727 13.4757 13.114 13.3253C13.2553 13.1833 13.3301 12.9995 13.3301 12.799C13.3301 12.5984 13.2553 12.4146 13.114 12.2726C12.9727 12.1222 12.7899 12.047 12.5822 12.047H11.0781C11.0781 11.9384 11.0781 11.8298 11.0781 11.7211C11.0781 11.6376 11.0781 11.554 11.0781 11.4705H12.5822C12.7899 11.4705 12.9727 11.3953 13.114 11.2449C13.2553 11.1029 13.3301 10.9191 13.3301 10.7185C13.3301 10.518 13.2553 10.3342 13.114 10.1922C12.9727 10.0418 12.7899 9.96658 12.5822 9.96658H11.3274C11.4022 9.74099 11.4936 9.54047 11.5933 9.38172C11.7761 9.08929 11.9922 8.88877 12.2415 8.76345C12.4991 8.62141 12.7899 8.55457 13.1057 8.55457C13.355 8.55457 13.6043 8.58799 13.8453 8.65483C14.0863 8.72167 14.2857 8.82193 14.4685 8.96397C14.6347 9.08929 14.8092 9.17285 14.992 9.17285H15.0003C15.1748 9.16449 15.3244 9.106 15.4491 8.99739C15.5737 8.88877 15.6485 8.74674 15.6984 8.58799C15.7482 8.42089 15.7565 8.25379 15.715 8.08668C15.6734 7.90287 15.5654 7.74412 15.4075 7.61044C15.0585 7.30966 14.6763 7.09243 14.2441 6.95875C13.8286 6.82507 13.4132 6.76658 12.9893 6.76658C12.3162 6.76658 11.7096 6.90862 11.1695 7.20104C10.6293 7.49347 10.1806 7.91958 9.83159 8.47937C9.56567 8.91384 9.36624 9.40679 9.23328 9.98329H8.60173C8.39398 9.98329 8.21116 10.0585 8.06158 10.2005C7.91201 10.3426 7.83722 10.5264 7.83722 10.7352C7.83722 10.9441 7.91201 11.1363 8.06158 11.2783C8.21116 11.4204 8.39398 11.4872 8.60173 11.4872H9.04215C9.04215 11.5708 9.04215 11.6627 9.04215 11.7462C9.04215 11.8465 9.04215 11.9551 9.04215 12.0637H8.60173C8.39398 12.0637 8.21116 12.1389 8.06158 12.2809L8.0782 12.2559Z"/></svg>
        Réserver
    </a>
</div>

<script>
const photos=[
    @foreach($ad->photos as $p)"{{ $p->url }}",@endforeach
];
const cTrack=document.getElementById('cTrack');
let cur=0;
function setDot(i){
    document.querySelectorAll('.dot').forEach((d,j)=>d.classList.toggle('active',j===i));
    const s=document.getElementById('slideNum');if(s)s.textContent=i+1;
    cur=i;
}
if(cTrack){
    cTrack.addEventListener('scroll',()=>{
        const i=Math.round(cTrack.scrollLeft/cTrack.offsetWidth);
        setDot(i);
    },{passive:true});
}
let lbCur=0;
function openLB(i){lbCur=i;lbUpd();document.getElementById('lightbox').classList.add('open');document.body.style.overflow='hidden';}
function closeLB(){document.getElementById('lightbox').classList.remove('open');document.body.style.overflow='';}
function lbSlide(d){lbCur=(lbCur+d+photos.length)%photos.length;lbUpd();}
function lbGo(i){lbCur=i;lbUpd();}
function lbUpd(){
    document.getElementById('lbImg').src=photos[lbCur];
    document.getElementById('lbCount').textContent=(lbCur+1)+' / '+photos.length;
    document.querySelectorAll('.lb-th').forEach((t,i)=>t.classList.toggle('active',i===lbCur));
}
const lb=document.getElementById('lightbox');
let lbTx=0;
if(lb){
    lb.addEventListener('touchstart',e=>{lbTx=e.touches[0].clientX;},{passive:true});
    lb.addEventListener('touchend',e=>{const d=e.changedTouches[0].clientX-lbTx;if(Math.abs(d)>40)lbSlide(d<0?1:-1);});
}
document.addEventListener('keydown',e=>{
    if(!lb||!lb.classList.contains('open'))return;
    if(e.key==='ArrowRight')lbSlide(1);
    if(e.key==='ArrowLeft')lbSlide(-1);
    if(e.key==='Escape')closeLB();
});
let faved={{ $isLiked ? 'true' : 'false' }};
const favOffset={{ $ad->likes_count ?? 0 }};
async function toggleFav(){
    const btn=document.getElementById('favBtn');
    if(btn.disabled)return;
    btn.disabled=true;
    try{
        const r=await fetch('{{ route("ads.like", $ad) }}',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
        });
        const d=await r.json();
        faved=d.liked;
        document.getElementById('favCount').textContent=d.count+favOffset;
        btn.classList.toggle('fav-red',faved);
        btn.setAttribute('aria-pressed',String(faved));
    }catch(e){console.error(e);}
    finally{btn.disabled=false;}
}
function shareAd(){
    if(navigator.share){navigator.share({title:'{{ addslashes($ad->title) }}',url:window.location.href});}
    else{navigator.clipboard.writeText(window.location.href).then(()=>alert('Lien copié !'));}
}
function toggleDesc(){
    const s=document.getElementById('descShort'),f=document.getElementById('descFull'),b=document.getElementById('descBtn');
    const open=f.style.display==='block';
    f.style.display=open?'none':'block';
    s.style.display=open?'-webkit-box':'none';
    b.textContent=open?'Voir plus':'Voir moins';
    b.setAttribute('aria-expanded',String(!open));
}
const sl=document.getElementById('finSlider');
if(sl){
    sl.addEventListener('input',()=>{
        document.getElementById('finVal').textContent=sl.value+' mois';
        const p=((sl.value-6)/(84-6))*100;
        sl.style.background=`linear-gradient(to right,var(--support) ${p}%,rgba(32,39,48,.15) ${p}%)`;
    });
}
const sBar=document.getElementById('stickyBar'),tc=document.getElementById('titleCard');
if(sBar){
    window.addEventListener('scroll',()=>{sBar.classList.toggle('visible',window.scrollY>340);},{passive:true});
}
</script>

</body>
</html>
