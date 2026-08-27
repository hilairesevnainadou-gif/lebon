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

        .ad-skeleton {
            height: 320px;
            border-radius: var(--radius-lg);
            background: linear-gradient(90deg, #eeece6 25%, #f5f4f0 37%, #eeece6 63%);
            background-size: 400% 100%;
            animation: skeleton-loading 1.4s ease infinite;
        }

        @keyframes skeleton-loading {
            0% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }
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
                <p class="section-subtitle">Vue administrateur — véhicules et PC, actives ou non — <span id="totalCount">{{ $counts['total'] }}</span> annonce(s)</p>
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
                <div class="filter-group" id="categoryFilters">
                    <button type="button" class="filter-link {{ !$category ? 'active' : '' }}" data-category="">Toutes catégories</button>
                    <button type="button" class="filter-link {{ $category === 'vehicule' ? 'active' : '' }}" data-category="vehicule">Véhicules</button>
                    <button type="button" class="filter-link {{ $category === 'pc' ? 'active' : '' }}" data-category="pc">PC</button>
                </div>
                <div class="filter-group" id="statusFilters">
                    <button type="button" class="filter-link {{ !$status ? 'active' : '' }}" data-status="">Tous statuts</button>
                    <button type="button" class="filter-link {{ $status === 'active' ? 'active' : '' }}" data-status="active">Actives</button>
                    <button type="button" class="filter-link {{ $status === 'paused' ? 'active' : '' }}" data-status="paused">En pause</button>
                    <button type="button" class="filter-link {{ $status === 'sold' ? 'active' : '' }}" data-status="sold">Vendues</button>
                </div>
            </div>

            <div class="ads-grid" id="adsGrid" aria-live="polite">
                <div class="ad-skeleton"></div>
                <div class="ad-skeleton"></div>
                <div class="ad-skeleton"></div>
            </div>

            <div class="empty-state" id="emptyState" style="display:none;">
                <div class="empty-title">Aucune annonce ne correspond à ces filtres</div>
                <div class="empty-text">Essayez d'élargir les filtres ci-dessus.</div>
            </div>

            <div class="empty-state" id="errorState" style="display:none;">
                <div class="empty-title">Impossible de charger les annonces</div>
                <div class="empty-text">Une erreur est survenue lors du chargement. <button type="button" id="retryBtn" style="border:none;background:none;color:var(--orange);font-weight:700;cursor:pointer;text-decoration:underline;">Réessayer</button></div>
            </div>

            <div class="pagination" id="paginationContainer" style="margin-top:24px;"></div>

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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // ========== Chargement de "Toutes les annonces" via axios ==========
    const ADMIN_ADS_DATA_URL = '{{ route('admin.ads.index.data') }}';

    const adsGrid = document.getElementById('adsGrid');
    const emptyState = document.getElementById('emptyState');
    const errorState = document.getElementById('errorState');
    const paginationContainer = document.getElementById('paginationContainer');
    const totalCount = document.getElementById('totalCount');

    let currentCategory = '{{ $category }}';
    let currentStatus = '{{ $status }}';
    let currentPage = 1;

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function statusLabel(status) {
        return { active: 'Active', sold: 'Vendue', paused: 'En pause' }[status] ?? status;
    }

    function adCardHTML(item) {
        const photoHtml = item.photo_url
            ? `<img src="${item.photo_url}" alt="${escapeHtml(item.title)}" loading="lazy">`
            : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                    <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>`;

        return `
            <a href="${item.show_url}" class="ad-card">
                <div class="ad-image">
                    ${photoHtml}
                    <div class="ad-badges">
                        <div class="ad-badge category">${item.is_pc ? 'PC' : 'Véhicule'}</div>
                        <div class="ad-badge ${item.status}">${statusLabel(item.status)}</div>
                    </div>
                </div>
                <div class="ad-content">
                    <div class="ad-title">${escapeHtml(item.title)}</div>
                    <div class="ad-price">${escapeHtml(item.price)}</div>
                    <div class="ad-seller">Vendeur : ${escapeHtml(item.seller)}</div>
                    <div class="ad-footer">
                        <span>${escapeHtml(item.city)}</span>
                        <span>${escapeHtml(item.published_at)}</span>
                    </div>
                </div>
            </a>`;
    }

    function renderAds(items) {
        if (!items.length) {
            adsGrid.style.display = 'none';
            paginationContainer.innerHTML = '';
            emptyState.style.display = '';
            return;
        }

        emptyState.style.display = 'none';
        adsGrid.style.display = '';
        adsGrid.innerHTML = items.map(adCardHTML).join('');
    }

    function renderPagination(pagination) {
        if (pagination.last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = '';
        for (let page = 1; page <= pagination.last_page; page++) {
            html += `<button type="button" class="page-link ${page === pagination.current_page ? 'active' : ''}" data-page="${page}">${page}</button>`;
        }
        paginationContainer.innerHTML = html;

        paginationContainer.querySelectorAll('.page-link').forEach(btn => {
            btn.addEventListener('click', () => {
                loadAds(parseInt(btn.dataset.page, 10));
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    function updateUrl() {
        const params = new URLSearchParams();
        if (currentCategory) params.set('category', currentCategory);
        if (currentStatus) params.set('status', currentStatus);
        const query = params.toString();
        const url = '{{ route('admin.ads.index') }}' + (query ? '?' + query : '');
        window.history.replaceState({}, '', url);
    }

    async function loadAds(page = 1) {
        errorState.style.display = 'none';
        emptyState.style.display = 'none';
        adsGrid.style.display = '';
        adsGrid.innerHTML = '<div class="ad-skeleton"></div><div class="ad-skeleton"></div><div class="ad-skeleton"></div>';

        try {
            const { data } = await axios.get(ADMIN_ADS_DATA_URL, {
                params: { page, category: currentCategory || undefined, status: currentStatus || undefined }
            });
            currentPage = data.pagination.current_page;
            if (totalCount) totalCount.textContent = data.pagination.total;
            renderAds(data.items);
            renderPagination(data.pagination);
        } catch (error) {
            adsGrid.style.display = 'none';
            paginationContainer.innerHTML = '';
            errorState.style.display = '';
        }
    }

    document.getElementById('retryBtn').addEventListener('click', () => loadAds(currentPage));

    document.querySelectorAll('#categoryFilters .filter-link').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#categoryFilters .filter-link').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentCategory = btn.dataset.category;
            updateUrl();
            loadAds(1);
        });
    });

    document.querySelectorAll('#statusFilters .filter-link').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#statusFilters .filter-link').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentStatus = btn.dataset.status;
            updateUrl();
            loadAds(1);
        });
    });

    loadAds();
</script>
</body>
</html>
