<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $ad->title }} - Espace Vendeur</title>
    <link rel="stylesheet" href="{{ asset('css/lebon.css') }}"/>
    <style>
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

        .ad-meta-line { font-size: 13px; color: var(--muted); }

        .ad-price { font-size: 32px; font-weight: 800; color: var(--orange); white-space: nowrap; }

        .show-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }
        @media (max-width: 900px) { .show-grid { grid-template-columns: 1fr; } }

        .show-col { display: flex; flex-direction: column; gap: 20px; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .card-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
        }

        .photo-grid img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .spec-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 480px) { .spec-grid { grid-template-columns: 1fr; } }

        .spec-item { font-size: 13px; }
        .spec-label { color: var(--muted); margin-bottom: 2px; }
        .spec-value { font-weight: 700; color: var(--text); }

        .feature-chip {
            display: inline-block;
            font-size: 12px;
            padding: 5px 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            margin: 0 6px 6px 0;
        }

        .seller-header { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }

        .seller-avatar {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 16px;
        }

        .seller-name { font-weight: 700; color: var(--text); }
        .seller-city { font-size: 12px; color: var(--muted); }

        .status-active { color: var(--green); font-weight: 700; }
    </style>
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('pc.index') }}" style="color:var(--muted);text-decoration:none;">Annonces PC</a>
                <span>›</span>
                <strong>{{ $ad->title }}</strong>
            </div>
            <div class="user-menu">
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content" style="padding:32px;">

            @if(session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            <div class="ad-header">
                <div class="ad-header-left">
                    <h1>{{ $ad->title }}</h1>
                    <div class="ad-meta-line">{{ $ad->city }} · Publiée {{ $ad->published_at?->diffForHumans() }}</div>
                </div>
                <div class="ad-price">{{ $ad->formatted_price }}</div>
            </div>

            <div class="show-grid">
                <div class="show-col">

                    <div class="card">
                        <div class="card-title">Photos</div>
                        @if($ad->photos->isNotEmpty())
                            <div class="photo-grid">
                                @foreach($ad->photos as $photo)
                                    <img src="{{ $photo->url }}" alt="{{ $ad->title }}">
                                @endforeach
                            </div>
                        @else
                            <p style="color:var(--muted);font-size:13px;">Aucune photo.</p>
                        @endif
                    </div>

                    @if($ad->computer)
                        <div class="card">
                            <div class="card-title">Caractéristiques</div>
                            <div class="spec-grid">
                                <div class="spec-item"><div class="spec-label">Marque / Modèle</div><div class="spec-value">{{ $ad->computer->full_name }}</div></div>
                                <div class="spec-item"><div class="spec-label">Processeur</div><div class="spec-value">{{ $ad->computer->cpu }}</div></div>
                                <div class="spec-item"><div class="spec-label">Mémoire vive</div><div class="spec-value">{{ $ad->computer->ram_gb }} Go</div></div>
                                <div class="spec-item"><div class="spec-label">Stockage</div><div class="spec-value">{{ $ad->computer->formatted_storage }}</div></div>
                                @if($ad->computer->gpu)
                                    <div class="spec-item"><div class="spec-label">Carte graphique</div><div class="spec-value">{{ $ad->computer->gpu }}</div></div>
                                @endif
                                @if($ad->computer->screen_size)
                                    <div class="spec-item"><div class="spec-label">Écran</div><div class="spec-value">{{ $ad->computer->screen_size }}"</div></div>
                                @endif
                                @if($ad->computer->os)
                                    <div class="spec-item"><div class="spec-label">Système</div><div class="spec-value">{{ $ad->computer->os }}</div></div>
                                @endif
                                @if($ad->computer->condition)
                                    <div class="spec-item"><div class="spec-label">État</div><div class="spec-value">{{ $ad->computer->condition }}</div></div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($ad->description)
                        <div class="card">
                            <div class="card-title">Description</div>
                            <p style="font-size:14px;color:var(--text);line-height:1.6;">{{ $ad->description }}</p>
                        </div>
                    @endif

                    @if($ad->features->isNotEmpty())
                        <div class="card">
                            <div class="card-title">Équipements</div>
                            @foreach($ad->features as $feature)
                                <span class="feature-chip">{{ $feature->label }}</span>
                            @endforeach
                        </div>
                    @endif

                </div>

                <div class="show-col">
                    <div class="card">
                        <div class="seller-header">
                            <div class="seller-avatar">{{ strtoupper(substr($ad->seller->pseudo ?? 'V', 0, 1)) }}</div>
                            <div>
                                <div class="seller-name">{{ $ad->seller->pseudo }}</div>
                                <div class="seller-city">{{ $ad->seller->city }}</div>
                            </div>
                        </div>
                        <div class="card-title" style="margin-bottom:6px;">Statut</div>
                        <div class="status-active">{{ $ad->status === 'active' ? 'Active' : $ad->status }}</div>
                    </div>
                </div>
            </div>

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
