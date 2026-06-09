<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <title>Mes annonces - Espace Vendeur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f7f6f3;
            color: #1c1c1c;
            min-height: 100vh;
        }

        :root {
            --orange: #e55a00;
            --orange-lt: #fff0e8;
            --orange-dark: #cc5000;
            --navy: #0e1e3d;
            --navy-light: #1a2a4a;
            --green: #1a7a4a;
            --green-light: #e8f5ef;
            --red: #d93025;
            --red-light: #fee2e2;
            --border: #e2e0da;
            --card: #ffffff;
            --bg: #f7f6f3;
            --text: #1c1c1c;
            --text-light: #6b6a66;
            --muted: #7a7870;
            --radius: 14px;
            --radius-lg: 20px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        /* Layout principal */
        .app {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* ========== SIDEBAR DESKTOP ========== */
        .sidebar {
            background: var(--navy);
            position: sticky;
            top: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 32px 0;
        }

        .sidebar-logo {
            padding: 0 24px 32px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 28px;
        }

        .sidebar-logo span {
            font-family: 'DM Serif Display', serif;
            font-size: 22px;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .sidebar-logo span em {
            color: #ff7a35;
            font-style: normal;
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 0 16px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: rgba(255, 255, 255, 0.5);
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
        }

        .nav-item.active {
            background: rgba(255, 121, 53, 0.18);
            color: #fff;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
        }

        .nav-text {
            font-size: 14px;
            font-weight: 600;
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .sidebar-footer {
            padding: 24px 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 16px;
        }

        /* ========== MAIN CONTENT ========== */
        .main {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar */
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
        }

        .breadcrumb strong {
            color: var(--text);
            font-weight: 700;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background: var(--orange);
            color: white;
            border: none;
            padding: 0 20px;
            height: 38px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: var(--orange-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--border);
            color: var(--text);
            padding: 0 16px;
            height: 38px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        /* Content */
        .content {
            flex: 1;
            padding: 32px;
        }

        /* Welcome Section */
        .welcome {
            margin-bottom: 32px;
            animation: fadeUp 0.4s ease;
        }

        .welcome h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            color: var(--text);
            margin-bottom: 8px;
        }

        .welcome p {
            font-size: 15px;
            color: var(--muted);
        }

        /* Stats Grid */
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
            animation: fadeUp 0.4s ease 0.05s both;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
            border-color: var(--orange);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--muted);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: var(--orange-lt);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 20px;
            height: 20px;
            stroke: var(--orange);
            stroke-width: 1.8;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 4px;
        }

        .stat-sub {
            font-size: 12px;
            color: var(--muted);
        }

        /* Toolbar */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--muted);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        .filter-btn.active {
            background: var(--orange);
            border-color: var(--orange);
            color: white;
        }

        .create-mobile {
            display: none;
        }

        /* Ads Grid */
        .ads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
            animation: fadeUp 0.4s ease 0.1s both;
        }

        .ad-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ad-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--orange);
        }

        .ad-image {
            position: relative;
            aspect-ratio: 16/10;
            background: linear-gradient(135deg, #f0efea, #e8e7e2);
            overflow: hidden;
        }

        .ad-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .ad-card:hover .ad-image img {
            transform: scale(1.05);
        }

        .ad-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 5px 12px;
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(4px);
        }

        .ad-badge.active {
            background: var(--green);
            color: white;
        }

        .ad-badge.sold {
            background: var(--muted);
            color: white;
        }

        .ad-badge.paused {
            background: #f5a623;
            color: white;
        }

        .ad-badge.draft {
            background: var(--orange);
            color: white;
        }

        .ad-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .ad-card:hover .ad-actions {
            opacity: 1;
        }

        .ad-action {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--text);
        }

        .ad-action:hover {
            background: var(--orange);
            color: white;
        }

        .ad-content {
            padding: 16px;
        }

        .ad-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ad-price {
            font-size: 20px;
            font-weight: 800;
            color: var(--orange);
            margin-bottom: 12px;
        }

        .ad-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .ad-meta-item {
            font-size: 11px;
            padding: 4px 10px;
            background: var(--bg);
            border-radius: 6px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .ad-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: var(--muted);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            background: var(--orange-lt);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .empty-icon svg {
            width: 36px;
            height: 36px;
            stroke: var(--orange);
            stroke-width: 1.5;
        }

        .empty-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .empty-text {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        /* Flash Message */
        .flash {
            margin-bottom: 24px;
            padding: 14px 20px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        .flash.success {
            background: var(--green-light);
            border: 1px solid rgba(26, 122, 74, 0.2);
            color: var(--green);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .page-link {
            padding: 8px 16px;
            border: 1px solid var(--border);
            background: var(--card);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .page-link:hover {
            border-color: var(--orange);
            color: var(--orange);
        }

        .page-link.active {
            background: var(--orange);
            border-color: var(--orange);
            color: white;
        }

        /* Modal de déconnexion */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.open {
            display: flex;
            animation: fadeIn 0.2s ease;
        }

        .modal-content {
            background: var(--card);
            border-radius: var(--radius-lg);
            max-width: 420px;
            width: 90%;
            padding: 28px;
            text-align: center;
            animation: slideUp 0.3s ease;
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            background: var(--red-light);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .modal-icon svg {
            width: 32px;
            height: 32px;
            stroke: var(--red);
            stroke-width: 1.8;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 12px;
        }

        .modal-text {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .modal-btn-cancel {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--text);
        }

        .modal-btn-cancel:hover {
            background: var(--border);
        }

        .modal-btn-confirm {
            background: var(--red);
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #b91c1c;
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            background: var(--navy);
            padding: 14px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 18px;
            color: white;
        }

        .mobile-logo em {
            color: #ff7a35;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
        }

        /* Mobile Sidebar */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            background: var(--navy);
            z-index: 200;
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
        }

        .mobile-sidebar.open {
            left: 0;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 199;
            display: none;
        }

        .overlay.open {
            display: block;
        }

        /* Animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .stats {
                gap: 16px;
            }
            .stat-value {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .app {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            .mobile-header {
                display: flex;
            }

            .topbar {
                display: none;
            }

            .content {
                padding: 20px;
            }

            .welcome h1 {
                font-size: 24px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-icon {
                width: 32px;
                height: 32px;
            }

            .stat-icon svg {
                width: 16px;
                height: 16px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .filters {
                justify-content: center;
            }

            .create-mobile {
                display: block;
                margin-top: 8px;
            }

            .ads-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .empty-state {
                padding: 40px 20px;
            }

            .empty-title {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .stats {
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-label {
                font-size: 10px;
            }

            .stat-value {
                font-size: 20px;
            }

            .filter-btn {
                padding: 6px 14px;
                font-size: 12px;
            }

            .ad-meta-item {
                font-size: 10px;
                padding: 3px 8px;
            }

            .ad-footer {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>

<div class="app">
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <span>Tableau de bord</span>
                <span>›</span>
                <strong>Mes annonces</strong>
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

        <div class="content">
            @if(session('success'))
                <div class="flash success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @php
                $totalAds = $ads->total();
                $activeAds = $ads->getCollection()->where('status', 'active')->count();
                $soldAds = $ads->getCollection()->where('status', 'sold')->count();
                $totalViews = $ads->getCollection()->sum(fn($a) => $a->views ?? 0);
            @endphp

            <div class="welcome">
                <h1>
                    Bonjour, {{ auth()->user()->name ?? 'Vendeur' }}
                    @if(auth()->user()->isAdmin())
                        <span style="display:inline-flex;align-items:center;gap:4px;background:var(--orange);color:#fff;font-size:11px;font-weight:800;letter-spacing:.5px;text-transform:uppercase;padding:4px 10px;border-radius:20px;vertical-align:middle;font-family:'DM Sans',sans-serif;">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
                            Admin
                        </span>
                    @endif
                </h1>
                <p>
                    @if(auth()->user()->isAdmin())
                        Vue administrateur — toutes les annonces et brouillons sont affichés
                    @else
                        Voici l'état de vos annonces et statistiques
                    @endif
                </p>
            </div>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Total annonces</span>
                        <div class="stat-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <line x1="3" y1="9" x2="21" y2="9"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ $totalAds }}</div>
                    <div class="stat-sub">annonce(s) publiée(s)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">En ligne</span>
                        <div class="stat-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value" style="color: var(--green);">{{ $activeAds }}</div>
                    <div class="stat-sub">annonce(s) active(s)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Vendues</span>
                        <div class="stat-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ $soldAds }}</div>
                    <div class="stat-sub">transaction(s)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-label">Vues totales</span>
                        <div class="stat-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-value">{{ number_format($totalViews) }}</div>
                    <div class="stat-sub">vues cumulées</div>
                </div>
            </div>

            <div class="toolbar">
                <div class="filters">
                    <button class="filter-btn active" data-filter="all">Toutes</button>
                    <button class="filter-btn" data-filter="active">Actives</button>
                    <button class="filter-btn" data-filter="sold">Vendues</button>
                    <button class="filter-btn" data-filter="paused">En pause</button>
                </div>
                <div class="create-mobile">
                    <a href="{{ route('ads.create') }}" class="btn-primary" style="width: 100%; justify-content: center;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 4v16M4 12h16"/>
                        </svg>
                        Nouvelle annonce
                    </a>
                </div>
            </div>

            @if($ads->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <line x1="9" y1="9" x2="15" y2="15"/>
                            <line x1="15" y1="9" x2="9" y2="15"/>
                        </svg>
                    </div>
                    <div class="empty-title">Aucune annonce pour le moment</div>
                    <div class="empty-text">Publiez votre première annonce et commencez à vendre</div>
                    <a href="{{ route('ads.create') }}" class="btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 4v16M4 12h16"/>
                        </svg>
                        Créer une annonce
                    </a>
                </div>
            @else
                <div class="ads-grid" id="adsGrid">
                    @foreach($ads as $ad)
                        @if($ad instanceof \App\Models\AdDraft)
                            {{-- Carte brouillon --}}
                            <div class="ad-card" data-status="draft">
                                <div class="ad-image" onclick="window.location='{{ route('ads.create', ['draft_id' => $ad->id]) }}'">
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:6px;">
                                        <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span style="font-size:11px;color:var(--muted);">Non publié</span>
                                    </div>
                                    <div class="ad-badge draft">Brouillon</div>
                                    <div class="ad-actions" onclick="event.stopPropagation()">
                                        <a href="{{ route('ads.create', ['draft_id' => $ad->id]) }}" class="ad-action" title="Reprendre">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="ad-content" onclick="window.location='{{ route('ads.create', ['draft_id' => $ad->id]) }}'">
                                    <div class="ad-title">{{ $ad->data['ad']['title'] ?? 'Brouillon sans titre' }}</div>
                                    @if(!empty($ad->data['ad']['price']))
                                        <div class="ad-price">{{ number_format((float)$ad->data['ad']['price'], 0, ',', ' ') }} €</div>
                                    @else
                                        <div class="ad-price" style="color:var(--muted);font-size:14px;font-weight:600;">Prix non renseigné</div>
                                    @endif
                                    @php
                                        $dBrand   = $ad->data['vehicle']['brand'] ?? null;
                                        $dYear    = $ad->data['vehicle']['year'] ?? null;
                                        $dMileage = $ad->data['vehicle']['mileage'] ?? null;
                                        $dGearbox = $ad->data['vehicle']['gearbox'] ?? null;
                                    @endphp
                                    @if($dBrand || $dYear)
                                        <div class="ad-meta">
                                            @if($dYear)<span class="ad-meta-item">{{ $dYear }}</span>@endif
                                            @if($dBrand)<span class="ad-meta-item">{{ $dBrand }}</span>@endif
                                            @if($dMileage)<span class="ad-meta-item">{{ number_format((int)$dMileage, 0, ',', ' ') }} km</span>@endif
                                            @if($dGearbox)<span class="ad-meta-item">{{ ucfirst($dGearbox) }}</span>@endif
                                        </div>
                                    @endif
                                    <div class="ad-footer">
                                        <span>{{ $ad->data['ad']['city'] ?? '—' }}</span>
                                        <span>{{ $ad->updated_at->diffForHumans() }}</span>
                                        <span>Brouillon</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Carte annonce publiée --}}
                            <div class="ad-card" data-status="{{ $ad->status }}">
                                <div class="ad-image" onclick="window.location='{{ route('ads.show', $ad) }}'">
                                    @if($ad->photos && $ad->photos->isNotEmpty())
                                        <img src="{{ $ad->photos->first()->url }}" alt="{{ $ad->title }}" loading="lazy">
                                    @else
                                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                            <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24">
                                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21 15 16 10 5 21"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ad-badge {{ $ad->status }}">
                                        @switch($ad->status)
                                            @case('active') Active @break
                                            @case('sold') Vendue @break
                                            @case('paused') En pause @break
                                            @default {{ $ad->status }}
                                        @endswitch
                                    </div>
                                    <div class="ad-actions" onclick="event.stopPropagation()">
                                        <a href="{{ route('ads.show', $ad) }}" class="ad-action">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="ad-content" onclick="window.location='{{ route('ads.show', $ad) }}'">
                                    <div class="ad-title">{{ $ad->title }}</div>
                                    <div class="ad-price">{{ $ad->formatted_price }}</div>
                                    @if($ad->vehicle)
                                        <div class="ad-meta">
                                            <span class="ad-meta-item">{{ $ad->vehicle->year }}</span>
                                            <span class="ad-meta-item">{{ number_format($ad->vehicle->mileage, 0, ',', ' ') }} km</span>
                                            <span class="ad-meta-item">{{ ucfirst($ad->vehicle->gearbox) }}</span>
                                        </div>
                                    @endif
                                    <div class="ad-footer">
                                        <span>{{ $ad->city }}</span>
                                        <span>{{ $ad->published_at?->diffForHumans() ?? 'Non publiée' }}</span>
                                        <span>{{ $ad->views ?? 0 }} vues</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if($ads->hasPages())
                    <div class="pagination">
                        {{ $ads->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation de déconnexion -->
<div class="modal" id="logoutModal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
            </svg>
        </div>
        <div class="modal-title">Déconnexion</div>
        <div class="modal-text">Êtes-vous sûr de vouloir vous déconnecter ? Vous devrez vous reconnecter pour accéder à votre espace vendeur.</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeLogoutModal()">Annuler</button>
            <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: inline;">
                @csrf
                <button type="submit" class="modal-btn modal-btn-confirm">Se déconnecter</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Filtrage par statut
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.ad-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;

            cards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Modal de déconnexion
    function openLogoutModal() {
        document.getElementById('logoutModal').classList.add('open');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.remove('open');
    }

    // Fermer la modal en cliquant en dehors
    document.getElementById('logoutModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('logoutModal')) {
            closeLogoutModal();
        }
    });

    // Menu mobile
    function toggleMobileMenu() {
        const sidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }

    // Auto-hide flash
    setTimeout(() => {
        const flash = document.querySelector('.flash');
        if (flash) {
            flash.style.transition = 'opacity 0.3s';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 300);
        }
    }, 5000);

    // Fermer le menu mobile si on clique sur un lien
    document.querySelectorAll('.mobile-sidebar .nav-item, .mobile-sidebar .logout-btn').forEach(el => {
        el.addEventListener('click', () => {
            toggleMobileMenu();
        });
    });
</script>

</body>
</html>
