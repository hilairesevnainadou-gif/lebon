<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <title>{{ $ad->title }} - Espace Vendeur</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        /* ---- Page : détail annonce ---- */

        .content { padding: 32px; }

        /* En-tête annonce */
        .ad-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }

        .ad-header-left h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--text);
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .ad-meta-line {
            font-size: 13px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ad-price {
            font-size: 32px;
            font-weight: 800;
            color: var(--orange);
            white-space: nowrap;
        }

        /* Layout deux colonnes */
        .show-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 24px;
            align-items: start;
        }

        .show-col { display: flex; flex-direction: column; gap: 20px; }

        /* Galerie photos */
        .gallery {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .gallery-grid {
            display: grid;
            gap: 3px;
            background: var(--border);
        }

        .gallery-grid.has-many {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 280px 160px;
        }

        .gallery-grid.single {
            grid-template-columns: 1fr;
            grid-template-rows: 340px;
        }

        .gallery-item {
            overflow: hidden;
            position: relative;
        }

        .gallery-item.main {
            grid-row: span 2;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }

        .gallery-item:hover img { transform: scale(1.04); }

        .gallery-more {
            position: absolute;
            inset: 0;
            background: rgba(14,30,61,.55);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            font-weight: 800;
        }

        .no-photo {
            aspect-ratio: 16/9;
            background: linear-gradient(135deg, #f0efea, #e8e7e2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            padding: 40px;
        }

        /* Section card */
        .section-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .section-card h2 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-card h2::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Infos véhicule */
        .info-table { display: grid; grid-template-columns: 1fr 1fr; }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 4px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
            gap: 12px;
        }

        .info-row:nth-last-child(-n+2) { border-bottom: none; }

        .info-label { color: var(--muted); flex-shrink: 0; }
        .info-value { font-weight: 700; color: var(--text); text-align: right; }

        /* Équipements */
        .equip-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .equip-tag {
            padding: 6px 14px;
            border: 1.5px solid var(--border);
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-light);
            background: var(--bg);
        }

        /* Description */
        .description-text {
            font-size: 14px;
            color: var(--text-light);
            line-height: 1.7;
            white-space: pre-line;
        }

        /* Colonne droite */
        .seller-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .seller-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .seller-avatar {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, var(--green), #12603a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .seller-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 3px;
        }

        .seller-city {
            font-size: 12px;
            color: var(--muted);
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            margin-bottom: 20px;
            font-size: 13px;
        }

        .status-row .label { color: var(--muted); }

        .status-active  { color: var(--green); font-weight: 700; }
        .status-sold    { color: var(--muted); font-weight: 700; }
        .status-paused  { color: #f5a623; font-weight: 700; }

        .share-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            height: 44px;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
            text-decoration: none;
            margin-bottom: 16px;
        }

        .share-btn:hover { background: var(--navy-light); color: #fff; }

        /* Bloc lien public */
        .share-link-box {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .share-link-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--navy);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 10px;
        }

        .share-link-row {
            display: flex;
            gap: 8px;
        }

        .share-link-input {
            flex: 1;
            height: 38px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0 12px;
            font-size: 12px;
            font-family: monospace;
            color: var(--text);
            outline: none;
            background: var(--bg);
        }

        /* Stats card */
        .stats-mini {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
        }

        .stats-mini-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .stats-mini-value {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
        }

        .stats-mini-value span { font-size: 14px; font-weight: 500; color: var(--muted); }

        /* Bouton like */
        .like-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .2s;
            color: var(--muted);
            flex-shrink: 0;
        }

        .like-btn:hover, .like-btn.liked {
            border-color: var(--orange);
            color: var(--orange);
            background: var(--orange-lt);
        }

        .like-btn svg { pointer-events: none; }

        /* Flash */
        .flash { animation: slideDown .3s ease; }

        /* Responsive */
        @media (max-width: 1024px) {
            .show-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .content { padding: 20px; }
            .ad-price { font-size: 24px; }
            .gallery-grid.has-many {
                grid-template-rows: 200px 120px;
            }
            .info-table { grid-template-columns: 1fr; }
            .info-row:nth-last-child(-n+2) { border-bottom: 1px solid var(--border); }
            .info-row:last-child { border-bottom: none; }
        }
    </style>
</head>
<body>

<div class="app">

    {{-- ===== SIDEBAR DESKTOP ===== --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <span>le<em>bon</em>coin</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('ads.index') }}" class="nav-item active">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="nav-text">Mes annonces</span>
            </a>
            <a href="#" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="nav-text">Alertes</span>
            </a>
            <a href="#" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-text">Transactions</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button onclick="openLogoutModal()" class="logout-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Déconnexion
            </button>
        </div>
    </aside>

    {{-- ===== MOBILE HEADER ===== --}}
    <div class="mobile-header">
        <button class="menu-toggle" onclick="toggleMobileMenu()">
            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
        <div class="mobile-logo">le<em>bon</em>coin</div>
        <a href="{{ route('ads.create') }}" style="color:white;">
            <svg width="22" height="22" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 4v16M4 12h16"/>
            </svg>
        </a>
    </div>

    {{-- ===== MOBILE SIDEBAR ===== --}}
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-logo">
            <span>le<em>bon</em>coin</span>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('ads.index') }}" class="nav-item active">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span class="nav-text">Mes annonces</span>
            </a>
            <a href="#" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="nav-text">Alertes</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button onclick="openLogoutModal()" class="logout-btn">Déconnexion</button>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleMobileMenu()"></div>

    {{-- ===== MAIN ===== --}}
    <div class="main">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('ads.index') }}">Mes annonces</a>
                <span>›</span>
                <strong style="max-width:400px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ad->title }}</strong>
            </div>
            <div class="user-menu">
                <a href="{{ route('ads.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 4v16M4 12h16"/>
                    </svg>
                    Nouvelle annonce
                </a>
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                </div>
            </div>
        </div>

        {{-- Contenu --}}
        <div class="content">

            @if(session('success'))
                <div class="flash success">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Titre & prix --}}
            <div class="ad-header anim-fade-up">
                <div class="ad-header-left">
                    <h1>{{ $ad->title }}</h1>
                    <div class="ad-meta-line">
                        <span>{{ $ad->city }}</span>
                        <span>·</span>
                        <span>{{ $ad->published_at?->diffForHumans() ?? 'Non publiée' }}</span>
                        <span>·</span>
                        @php
                            $badgeClass = match($ad->status) {
                                'active' => 'badge-active',
                                'sold'   => 'badge-sold',
                                'paused' => 'badge-paused',
                                default  => 'badge-draft',
                            };
                            $badgeLabel = match($ad->status) {
                                'active' => 'Active',
                                'sold'   => 'Vendue',
                                'paused' => 'En pause',
                                default  => ucfirst($ad->status),
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </div>
                </div>
                <div class="ad-price">{{ $ad->formatted_price }}</div>
            </div>

            {{-- Lien de partage --}}
            @if(session('share_url'))
                <div class="share-link-box" style="margin-bottom:24px;">
                    <div class="share-link-title">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        Lien public de partage
                    </div>
                    <div class="share-link-row">
                        <input type="text" readonly id="shareInput" class="share-link-input" value="{{ session('share_url') }}"/>
                        <button id="copyBtn" onclick="copyShare()" class="btn-primary" style="height:38px;padding:0 16px;font-size:12px;">Copier</button>
                    </div>
                </div>
            @endif

            {{-- Grille principale --}}
            <div class="show-grid">

                {{-- Colonne gauche --}}
                <div class="show-col">

                    {{-- Galerie --}}
                    @if($ad->photos->isNotEmpty())
                        <div class="gallery">
                            @if($ad->photos->count() === 1)
                                <div class="gallery-grid single">
                                    <div class="gallery-item">
                                        <img src="{{ $ad->photos->first()->url }}" alt="{{ $ad->title }}"/>
                                    </div>
                                </div>
                            @else
                                <div class="gallery-grid has-many">
                                    @foreach($ad->photos->take(3) as $i => $photo)
                                        <div class="gallery-item {{ $i === 0 ? 'main' : '' }}">
                                            <img src="{{ $photo->url }}" alt="{{ $ad->title }}"/>
                                            @if($loop->last && $ad->photos->count() > 3)
                                                <div class="gallery-more">+{{ $ad->photos->count() - 3 }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="section-card no-photo" style="padding:60px 40px;">
                            <svg width="40" height="40" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                            <span>Aucune photo</span>
                        </div>
                    @endif

                    {{-- Informations véhicule --}}
                    @if($ad->vehicle)
                        <div class="section-card">
                            <h2>Informations clés</h2>
                            <div class="info-table">
                                @foreach([
                                    'Marque'            => $ad->vehicle->brand,
                                    'Modèle'            => $ad->vehicle->model,
                                    'Année'             => $ad->vehicle->year,
                                    'Kilométrage'       => $ad->vehicle->formatted_mileage,
                                    'Carburant'         => ucfirst($ad->vehicle->fuel_type ?? ''),
                                    'Boîte de vitesses' => ucfirst($ad->vehicle->gearbox ?? ''),
                                    'Portes'            => $ad->vehicle->doors,
                                    'Places'            => $ad->vehicle->seats,
                                    'Carrosserie'       => $ad->vehicle->body_type,
                                    'Couleur'           => $ad->vehicle->color,
                                    'Puissance DIN'     => $ad->vehicle->din_power    ? $ad->vehicle->din_power    . ' Ch' : null,
                                    'Puissance fiscale' => $ad->vehicle->fiscal_power ? $ad->vehicle->fiscal_power . ' Cv' : null,
                                    "Crit'Air"          => $ad->vehicle->critair,
                                    'Sellerie'          => $ad->vehicle->upholstery,
                                    'État'              => $ad->vehicle->condition,
                                ] as $label => $value)
                                    @if($value)
                                        <div class="info-row">
                                            <span class="info-label">{{ $label }}</span>
                                            <span class="info-value">{{ $value }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Équipements --}}
                    @if($ad->features->isNotEmpty())
                        <div class="section-card">
                            <h2>Équipements</h2>
                            <div class="equip-list">
                                @foreach($ad->features as $feature)
                                    <span class="equip-tag">{{ $feature->label }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Description --}}
                    @if($ad->description)
                        <div class="section-card">
                            <h2>Description</h2>
                            <p class="description-text">{{ $ad->description }}</p>
                        </div>
                    @endif

                </div>

                {{-- Colonne droite --}}
                <div class="show-col">

                    {{-- Vendeur --}}
                    <div class="seller-card">
                        <div class="seller-header">
                            <div class="seller-avatar">
                                {{ strtoupper(substr($ad->seller->first_name ?? 'V', 0, 1)) }}
                            </div>
                            <div>
                                <div class="seller-name">{{ $ad->seller->full_name }}</div>
                                <div class="seller-city">{{ $ad->seller->city }}</div>
                            </div>
                        </div>

                        <div class="status-row">
                            <span class="label">Statut de l'annonce</span>
                            <span class="status-{{ $ad->status }}">
                                @switch($ad->status)
                                    @case('active') ✓ Active @break
                                    @case('sold')   Vendue @break
                                    @case('paused') ⏸ En pause @break
                                    @default {{ ucfirst($ad->status) }}
                                @endswitch
                            </span>
                        </div>

                        <a href="{{ route('ads.share', $ad) }}" class="share-btn">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="18" cy="5" r="3"/>
                                <circle cx="6" cy="12" r="3"/>
                                <circle cx="18" cy="19" r="3"/>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                            </svg>
                            Obtenir le lien public
                        </a>

                        <a href="{{ route('ads.index') }}" class="btn-outline" style="width:100%;justify-content:center;height:40px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M19 12H5M12 5l-7 7 7 7"/>
                            </svg>
                            Retour aux annonces
                        </a>
                    </div>

                    {{-- Photos --}}
                    <div class="stats-mini">
                        <div class="stats-mini-label">Photos</div>
                        <div class="stats-mini-value">
                            {{ $ad->photos->count() }} <span>/ 12</span>
                        </div>
                    </div>

                    {{-- Vues --}}
                    <div class="stats-mini">
                        <div class="stats-mini-label">Vues totales</div>
                        <div class="stats-mini-value">
                            {{ number_format($ad->views) }} <span>vues</span>
                        </div>
                    </div>

                    {{-- Likes --}}
                    <div class="stats-mini">
                        <div class="stats-mini-label">Favoris</div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
                            <div class="stats-mini-value" id="likesCount">
                                {{ $likesCount }} <span>j'aime</span>
                            </div>
                            <button
                                id="likeBtn"
                                class="like-btn {{ $isLiked ? 'liked' : '' }}"
                                onclick="toggleLike()"
                                title="{{ $isLiked ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                            >
                                <svg width="20" height="20"
                                     fill="{{ $isLiked ? 'currentColor' : 'none' }}"
                                     stroke="currentColor" stroke-width="2"
                                     viewBox="0 0 24 24">
                                    <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de déconnexion --}}
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-icon danger">
            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        </div>
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous reconnecter pour accéder à votre espace vendeur.</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">Annuler</button>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="modal-btn modal-btn-confirm">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>

<script src="/js/lebon.js"></script>
<script>
async function toggleLike() {
    const btn      = document.getElementById('likeBtn');
    const countEl  = document.getElementById('likesCount');
    if (btn.disabled) return;
    btn.disabled = true;

    try {
        const resp = await fetch('{{ route("ads.like", $ad) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await resp.json();

        const svg  = btn.querySelector('svg');
        const path = btn.querySelector('path');

        if (data.liked) {
            btn.classList.add('liked');
            path.setAttribute('fill', 'currentColor');
            btn.title = 'Retirer des favoris';
        } else {
            btn.classList.remove('liked');
            path.setAttribute('fill', 'none');
            btn.title = 'Ajouter aux favoris';
        }

        countEl.innerHTML = data.count + " <span>j'aime</span>";
    } catch (e) {
        console.error(e);
    } finally {
        btn.disabled = false;
    }
}
</script>
</body>
</html>
