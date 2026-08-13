<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Annonces PC - Espace Vendeur</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        .ads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
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

        .ad-badge {
            position: absolute;
            top: 12px; left: 12px;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            background: rgba(26,122,74,.9);
            color: #fff;
        }

        .ad-content { padding: 16px; }

        .ad-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .ad-price { font-size: 20px; font-weight: 800; color: var(--orange); margin-bottom: 12px; }

        .ad-meta { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }

        .ad-meta-item {
            font-size: 11px;
            padding: 4px 10px;
            background: var(--bg);
            border-radius: 6px;
            color: var(--text-light);
        }

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

        .empty-icon {
            width: 80px; height: 80px;
            background: var(--orange-lt);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-icon svg { width: 36px; height: 36px; stroke: var(--orange); stroke-width: 1.5; }

        .empty-title { font-size: 22px; font-weight: 700; color: var(--text); margin-bottom: 8px; }
        .empty-text { font-size: 14px; color: var(--muted); margin-bottom: 24px; }
    </style>
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <strong>Annonces PC</strong>
            </div>
            <div class="user-menu">
                <a href="{{ route('pc.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg>
                    Nouvelle annonce PC
                </a>
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom:24px;">
                <h1 class="section-title">Annonces PC</h1>
                <p class="section-subtitle">Espace dédié à la vente d'ordinateurs — {{ $ads->total() }} annonce(s)</p>
            </div>

            @if($ads->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <div class="empty-title">Aucune annonce PC pour le moment</div>
                    <div class="empty-text">Publiez votre première annonce d'ordinateur</div>
                    <a href="{{ route('pc.create') }}" class="btn-primary">Créer une annonce PC</a>
                </div>
            @else
                <div class="ads-grid">
                    @foreach($ads as $ad)
                        <a href="{{ route('pc.show', $ad) }}" class="ad-card">
                            <div class="ad-image">
                                @if($ad->photos->isNotEmpty())
                                    <img src="{{ $ad->photos->first()->url }}" alt="{{ $ad->title }}" loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    </div>
                                @endif
                                <div class="ad-badge">{{ $ad->status === 'active' ? 'Active' : $ad->status }}</div>
                            </div>
                            <div class="ad-content">
                                <div class="ad-title">{{ $ad->title }}</div>
                                <div class="ad-price">{{ $ad->formatted_price }}</div>
                                @if($ad->computer)
                                    <div class="ad-meta">
                                        <span class="ad-meta-item">{{ $ad->computer->cpu }}</span>
                                        <span class="ad-meta-item">{{ $ad->computer->ram_gb }} Go RAM</span>
                                        <span class="ad-meta-item">{{ $ad->computer->formatted_storage }}</span>
                                    </div>
                                @endif
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

<script src="/js/lebon.js"></script>
</body>
</html>
