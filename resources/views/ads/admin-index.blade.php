<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Toutes les annonces - Administration</title>
    <link rel="stylesheet" href="{{ asset('css/lebon.css') }}"/>
    <style>
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-mini {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 18px;
        }

        .stat-mini-value { font-size: 24px; font-weight: 800; color: var(--text); }
        .stat-mini-label { font-size: 12px; color: var(--muted); margin-top: 2px; }

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-group { display: flex; gap: 6px; flex-wrap: wrap; }

        .filter-link {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text-light);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: all .15s ease;
        }

        .filter-link:hover { border-color: var(--orange); color: var(--orange); }
        .filter-link.active { background: var(--orange); border-color: var(--orange); color: #fff; }

        .ads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .ad-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
        }

        .ad-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-lg); }

        .ad-image {
            position: relative;
            aspect-ratio: 16/10;
            background: linear-gradient(135deg, #f0efea, #e8e7e2);
            overflow: hidden;
        }

        .ad-image img { width: 100%; height: 100%; object-fit: cover; }

        .ad-badges { position: absolute; top: 12px; left: 12px; display: flex; gap: 6px; }

        .ad-badge {
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #fff;
        }

        .ad-badge.category { background: rgba(14,30,61,.85); }
        .ad-badge.active { background: rgba(26,122,74,.9); }
        .ad-badge.paused  { background: rgba(245,166,35,.9); }
        .ad-badge.sold    { background: rgba(122,120,112,.9); }

        .ad-content { padding: 16px; }

        .ad-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .ad-price { font-size: 20px; font-weight: 800; color: var(--orange); margin-bottom: 12px; }

        .ad-seller { font-size: 12px; color: var(--text-light); margin-bottom: 12px; }

        .ad-footer {
            display: flex;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: var(--muted);
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
        }

        .empty-title { font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .empty-text { font-size: 14px; color: var(--muted); }
    </style>
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <strong>Toutes les annonces</strong>
            </div>
            <div class="user-menu">
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content">

            <div style="margin-bottom:24px;">
                <h1 class="section-title">Toutes les annonces</h1>
                <p class="section-subtitle">Vue administrateur — véhicules et PC, actives ou non — {{ $ads->total() }} annonce(s)</p>
            </div>

            <div class="stats-row">
                <div class="stat-mini"><div class="stat-mini-value">{{ $counts['total'] }}</div><div class="stat-mini-label">Total</div></div>
                <div class="stat-mini"><div class="stat-mini-value">{{ $counts['vehicule'] }}</div><div class="stat-mini-label">Véhicules</div></div>
                <div class="stat-mini"><div class="stat-mini-value">{{ $counts['pc'] }}</div><div class="stat-mini-label">PC</div></div>
                <div class="stat-mini"><div class="stat-mini-value" style="color:var(--green);">{{ $counts['active'] }}</div><div class="stat-mini-label">Actives</div></div>
                <div class="stat-mini"><div class="stat-mini-value">{{ $counts['paused'] }}</div><div class="stat-mini-label">En pause</div></div>
                <div class="stat-mini"><div class="stat-mini-value">{{ $counts['sold'] }}</div><div class="stat-mini-label">Vendues</div></div>
            </div>

            <div class="filters-row">
                <div class="filter-group">
                    <a href="{{ route('admin.ads.index', array_filter(['status' => $status])) }}"
                       class="filter-link {{ !$category ? 'active' : '' }}">Toutes catégories</a>
                    <a href="{{ route('admin.ads.index', array_filter(['category' => 'vehicule', 'status' => $status])) }}"
                       class="filter-link {{ $category === 'vehicule' ? 'active' : '' }}">Véhicules</a>
                    <a href="{{ route('admin.ads.index', array_filter(['category' => 'pc', 'status' => $status])) }}"
                       class="filter-link {{ $category === 'pc' ? 'active' : '' }}">PC</a>
                </div>
                <div class="filter-group">
                    <a href="{{ route('admin.ads.index', array_filter(['category' => $category])) }}"
                       class="filter-link {{ !$status ? 'active' : '' }}">Tous statuts</a>
                    <a href="{{ route('admin.ads.index', array_filter(['category' => $category, 'status' => 'active'])) }}"
                       class="filter-link {{ $status === 'active' ? 'active' : '' }}">Actives</a>
                    <a href="{{ route('admin.ads.index', array_filter(['category' => $category, 'status' => 'paused'])) }}"
                       class="filter-link {{ $status === 'paused' ? 'active' : '' }}">En pause</a>
                    <a href="{{ route('admin.ads.index', array_filter(['category' => $category, 'status' => 'sold'])) }}"
                       class="filter-link {{ $status === 'sold' ? 'active' : '' }}">Vendues</a>
                </div>
            </div>

            @if($ads->isEmpty())
                <div class="empty-state">
                    <div class="empty-title">Aucune annonce ne correspond à ces filtres</div>
                    <div class="empty-text">Essayez d'élargir les filtres ci-dessus.</div>
                </div>
            @else
                <div class="ads-grid">
                    @foreach($ads as $ad)
                        @php
                            $isPc = $ad->category === 'pc';
                            $showRoute = $isPc ? route('pc.show', $ad) : route('ads.show', $ad);
                        @endphp
                        <a href="{{ $showRoute }}" class="ad-card">
                            <div class="ad-image">
                                @if($ad->photos->isNotEmpty())
                                    <img src="{{ $ad->photos->first()->url }}" alt="{{ $ad->title }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                    </div>
                                @endif
                                <div class="ad-badges">
                                    <div class="ad-badge category">{{ $isPc ? 'PC' : 'Véhicule' }}</div>
                                    <div class="ad-badge {{ $ad->status }}">
                                        @switch($ad->status)
                                            @case('active') Active @break
                                            @case('sold') Vendue @break
                                            @case('paused') En pause @break
                                            @default {{ $ad->status }}
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            <div class="ad-content">
                                <div class="ad-title">{{ $ad->title }}</div>
                                <div class="ad-price">{{ $ad->formatted_price }}</div>
                                <div class="ad-seller">Vendeur : {{ $ad->seller->pseudo ?? '—' }}</div>
                                <div class="ad-footer">
                                    <span>{{ $ad->city }}</span>
                                    <span>{{ $ad->published_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($ads->hasPages())
                    <div class="pagination" style="margin-top:24px;">
                        {{ $ads->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>

<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-icon danger">
            <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        </div>
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">Êtes-vous sûr de vouloir vous déconnecter ?</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">Annuler</button>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="modal-btn modal-btn-confirm">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/lebon.js') }}"></script>
</body>
</html>
