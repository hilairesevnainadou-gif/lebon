<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        .table-wrap {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--bg);
            border-bottom: 1px solid var(--border);
        }

        th {
            padding: 12px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--muted);
        }

        td {
            padding: 14px 20px;
            font-size: 14px;
            color: var(--text);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td { background: #fafaf8; }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-ava {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--orange), var(--orange-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-ava.admin {
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
        }

        .user-name { font-weight: 700; }
        .user-email { font-size: 12px; color: var(--muted); }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .role-admin {
            background: rgba(14,30,61,.1);
            color: var(--navy);
        }

        .role-user {
            background: var(--bg);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .actions { display: flex; gap: 8px; }

        .btn-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            background: var(--card);
            cursor: pointer;
            transition: all .15s;
            color: var(--muted);
            text-decoration: none;
        }

        .btn-icon:hover { border-color: var(--orange); color: var(--orange); }
        .btn-icon.delete:hover { border-color: var(--red); color: var(--red); }

        .me-badge {
            font-size: 10px;
            background: var(--orange-lt);
            color: var(--orange);
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
        }
    </style>
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    {{-- Main --}}
    <div class="main">
        <div class="topbar">
            <div class="breadcrumb">
                <span>Administration</span>
                <span>›</span>
                <strong>Utilisateurs</strong>
            </div>
            <div class="user-menu">
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg>
                    Nouvel utilisateur
                </a>
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content">

            @if(session('success'))
                <div class="flash success">
                    <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash error">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:24px;gap:16px;flex-wrap:wrap;">
                <div>
                    <h1 class="section-title">Utilisateurs</h1>
                    <p class="section-subtitle">{{ $users->total() }} compte(s) enregistré(s)</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 4v16M4 12h16"/></svg>
                    Créer un utilisateur
                </a>
            </div>

            <div class="table-wrap anim-fade-up">
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Rôle</th>
                            <th>Créé le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-ava {{ $user->is_admin ? 'admin' : '' }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="user-name">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span class="me-badge">Vous</span>
                                                @endif
                                            </div>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="role-badge role-admin">
                                            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01z"/></svg>
                                            Admin
                                        </span>
                                    @else
                                        <span class="role-badge role-user">Vendeur</span>
                                    @endif
                                </td>
                                <td style="color:var(--muted);font-size:13px;">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('users.edit', $user) }}" class="btn-icon" title="Modifier">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4z"/></svg>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button
                                                class="btn-icon delete"
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                            >
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:48px;color:var(--muted);">
                                    Aucun utilisateur trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="pagination" style="margin-top:24px;">
                    {{ $users->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal déconnexion --}}
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

{{-- Modal suppression utilisateur --}}
<div class="modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon danger">
            <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
        </div>
        <div class="modal-title">Supprimer l'utilisateur</div>
        <div class="modal-text" id="deleteModalText">Confirmer la suppression ?</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="document.getElementById('deleteModal').classList.remove('open')">Annuler</button>
            <form method="POST" id="deleteForm" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn modal-btn-confirm">Supprimer</button>
            </form>
        </div>
    </div>
</div>

<script src="/js/lebon.js"></script>
<script>
function confirmDelete(id, name) {
    document.getElementById('deleteModalText').textContent = 'Supprimer le compte de "' + name + '" ? Cette action est irréversible.';
    document.getElementById('deleteForm').action = '/utilisateurs/' + id;
    document.getElementById('deleteModal').classList.add('open');
}
</script>
</body>
</html>
