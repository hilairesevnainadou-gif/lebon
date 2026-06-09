<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $user ? 'Modifier l\'utilisateur' : 'Créer un utilisateur' }}</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px;
            max-width: 560px;
        }

        .form-section-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: .3px;
            margin-bottom: 8px;
        }

        .form-label .req { color: var(--orange); margin-left: 2px; }

        .form-control {
            width: 100%;
            height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0 16px;
            font-size: 14px;
            color: var(--text);
            background: var(--bg);
            outline: none;
            transition: all .15s;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(229,90,0,.1);
        }

        .form-control.is-error { border-color: var(--red); }

        .field-error {
            font-size: 12px;
            color: var(--red);
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 5px;
        }

        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color .15s;
        }

        .toggle-row:hover { border-color: var(--orange); }

        .toggle-row-label {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .toggle-row-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .toggle-row-sub {
            font-size: 12px;
            color: var(--muted);
        }

        .toggle-switch {
            width: 44px; height: 24px;
            background: var(--border);
            border-radius: 20px;
            position: relative;
            transition: background .2s;
            flex-shrink: 0;
        }

        .toggle-switch.on { background: var(--orange); }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 18px; height: 18px;
            background: #fff;
            border-radius: 50%;
            top: 3px; left: 3px;
            transition: left .2s;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }

        .toggle-switch.on::after { left: 23px; }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
        }

        .btn-submit {
            flex: 1;
            height: 46px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s;
        }

        .btn-submit:hover { background: var(--orange-dark); transform: translateY(-1px); }

        .btn-cancel {
            height: 46px;
            padding: 0 24px;
            background: var(--bg);
            color: var(--text);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .btn-cancel:hover { border-color: var(--orange); color: var(--orange); }

        .pass-toggle {
            position: relative;
        }

        .pass-toggle .form-control { padding-right: 48px; }

        .pass-eye {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 0;
            line-height: 0;
        }

        .pass-eye:hover { color: var(--orange); }
    </style>
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="sidebar-logo"><span>le<em>bon</em>coin</span></div>
        <nav class="sidebar-nav">
            <a href="{{ route('ads.index') }}" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span class="nav-text">Mes annonces</span>
            </a>
            <a href="{{ route('users.index') }}" class="nav-item active">
                <svg class="nav-icon" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                <span class="nav-text">Utilisateurs</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <button onclick="openLogoutModal()" class="logout-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Déconnexion
            </button>
        </div>
    </aside>

    <div class="mobile-header">
        <button class="menu-toggle" onclick="toggleMobileMenu()">
            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div class="mobile-logo">le<em>bon</em>coin</div>
        <span></span>
    </div>
    <div class="mobile-sidebar" id="mobileSidebar">
        <div class="sidebar-logo"><span>le<em>bon</em>coin</span></div>
        <nav class="sidebar-nav">
            <a href="{{ route('ads.index') }}" class="nav-item">Mes annonces</a>
            <a href="{{ route('users.index') }}" class="nav-item active">Utilisateurs</a>
        </nav>
        <div class="sidebar-footer">
            <button onclick="openLogoutModal()" class="logout-btn">Déconnexion</button>
        </div>
    </div>
    <div class="overlay" id="overlay" onclick="toggleMobileMenu()"></div>

    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('users.index') }}" style="color:var(--muted);text-decoration:none;">Utilisateurs</a>
                <span>›</span>
                <strong>{{ $user ? 'Modifier' : 'Créer' }}</strong>
            </div>
            <div class="user-menu">
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content">
            <div style="margin-bottom:24px;">
                <h1 class="section-title">{{ $user ? 'Modifier l\'utilisateur' : 'Créer un utilisateur' }}</h1>
                <p class="section-subtitle">
                    {{ $user ? "Mettez à jour les informations de {$user->name}." : 'Ajoutez un nouveau compte à la plateforme.' }}
                </p>
            </div>

            <div class="form-card anim-fade-up">
                <div class="form-section-title">Informations du compte</div>

                <form
                    method="POST"
                    action="{{ $user ? route('users.update', $user) : route('users.store') }}"
                >
                    @csrf
                    @if($user) @method('PUT') @endif

                    <div class="form-group">
                        <label class="form-label" for="name">Nom complet <span class="req">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user?->name) }}"
                            placeholder="Ex: Jean Dupont"
                            autocomplete="name"
                            class="form-control @error('name') is-error @enderror"
                        />
                        @error('name')
                            <div class="field-error">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Adresse email <span class="req">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user?->email) }}"
                            placeholder="nom@entreprise.com"
                            autocomplete="email"
                            class="form-control @error('email') is-error @enderror"
                        />
                        @error('email')
                            <div class="field-error">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            Mot de passe {{ $user ? '' : '*' }}
                            @if($user) <span class="req"></span> @endif
                        </label>
                        <div class="pass-toggle">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="{{ $user ? 'Laisser vide pour ne pas changer' : '••••••••' }}"
                                autocomplete="new-password"
                                class="form-control @error('password') is-error @enderror"
                            />
                            <button type="button" class="pass-eye" onclick="togglePass('password', 'eye1')">
                                <svg id="eye1" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-error">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                {{ $message }}
                            </div>
                        @else
                            <div class="hint">Minimum 8 caractères, lettres et chiffres.</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                        <div class="pass-toggle">
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="••••••••"
                                autocomplete="new-password"
                                class="form-control"
                            />
                            <button type="button" class="pass-eye" onclick="togglePass('password_confirmation', 'eye2')">
                                <svg id="eye2" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <div
                            class="toggle-row"
                            onclick="toggleAdmin()"
                            id="adminToggleRow"
                        >
                            <div class="toggle-row-label">
                                <span class="toggle-row-title">Administrateur</span>
                                <span class="toggle-row-sub">Accès complet à la gestion des utilisateurs et des annonces</span>
                            </div>
                            <div class="toggle-switch {{ old('is_admin', $user?->is_admin) ? 'on' : '' }}" id="adminSwitch"></div>
                        </div>
                        <input
                            type="hidden"
                            name="is_admin"
                            id="isAdminInput"
                            value="{{ old('is_admin', $user?->is_admin) ? '1' : '0' }}"
                        />
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('users.index') }}" class="btn-cancel">Annuler</a>
                        <button type="submit" class="btn-submit">
                            {{ $user ? 'Enregistrer les modifications' : 'Créer l\'utilisateur' }}
                        </button>
                    </div>

                </form>
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

<script src="/js/lebon.js"></script>
<script>
function toggleAdmin() {
    const input  = document.getElementById('isAdminInput');
    const sw     = document.getElementById('adminSwitch');
    const newVal = input.value === '0' ? '1' : '0';
    input.value  = newVal;
    sw.classList.toggle('on', newVal === '1');
}

function togglePass(inputId, eyeId) {
    const inp = document.getElementById(inputId);
    inp.type  = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
