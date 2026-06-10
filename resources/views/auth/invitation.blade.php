<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Activer votre compte</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        body {
            background: var(--navy);
            display: flex;
            min-height: 100vh;
            align-items: stretch;
        }

        .login-wrap {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .login-form-col {
            flex: 1;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .login-form-inner {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            margin-bottom: 36px;
        }

        .login-logo span {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--navy);
            letter-spacing: -.3px;
        }

        .login-logo span em {
            color: var(--orange);
            font-style: normal;
        }

        .icon-box {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: var(--orange-lt);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--text);
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .form-field { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: .3px;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0 16px;
            font-size: 14px;
            color: var(--text);
            background: var(--card);
            outline: none;
            transition: all .15s;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(229,90,0,.1);
        }

        .form-input.error { border-color: var(--red); }

        .field-error {
            font-size: 12px;
            color: var(--red);
            margin-top: 6px;
        }

        .pass-wrap { position: relative; }
        .pass-wrap .form-input { padding-right: 48px; }

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

        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        .strength-seg {
            flex: 1; height: 3px;
            border-radius: 2px;
            background: var(--border);
            transition: background .3s;
        }

        .strength-label {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: 8px;
        }

        .btn-submit:hover { background: var(--orange-dark); transform: translateY(-1px); }

        .alert-error {
            background: var(--red-light);
            border: 1px solid rgba(217,48,37,.25);
            border-left: 3px solid var(--red);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--red);
            font-weight: 600;
            margin-bottom: 24px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 28px;
        }

        .user-badge-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--navy);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .user-badge-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
        }

        .user-badge-email {
            font-size: 12px;
            color: var(--muted);
        }

        .login-aside {
            width: 480px;
            flex-shrink: 0;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
        }

        .aside-glow {
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,122,53,.2) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .aside-logo {
            font-family: 'DM Serif Display', serif;
            font-size: 42px;
            color: #fff;
            letter-spacing: -.5px;
            margin-bottom: 20px;
            position: relative;
        }

        .aside-logo em { color: #ff7a35; font-style: normal; }

        .aside-tagline {
            font-size: 17px;
            color: rgba(255,255,255,.6);
            text-align: center;
            line-height: 1.6;
            max-width: 300px;
            position: relative;
        }

        @media (max-width: 900px) { .login-aside { display: none; } }
        @media (max-width: 480px) { .login-form-col { padding: 32px 20px; } }
    </style>
</head>
<body>

<div class="login-wrap">
    <div class="login-form-col">
        <div class="login-form-inner">

            <div class="login-logo">
                <span>le<em>bon</em>véhicule</span>
            </div>

            <div class="icon-box">
                <svg width="24" height="24" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>

            <h1 class="login-title">Activez votre compte</h1>
            <p class="login-subtitle">Bienvenue ! Définissez votre mot de passe pour accéder à la plateforme.</p>

            <div class="user-badge">
                <div class="user-badge-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <div class="user-badge-name">{{ $user->name }}</div>
                    <div class="user-badge-email">{{ $user->email }}</div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('invitation.activate', $token) }}">
                @csrf

                <div class="form-field">
                    <label class="form-label" for="password">Mot de passe</label>
                    <div class="pass-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            autofocus
                            autocomplete="new-password"
                            class="form-input @error('password') error @enderror"
                            oninput="updateStrength(this.value)"
                        />
                        <button type="button" class="pass-eye" onclick="togglePass('password','eye1')" tabindex="-1">
                            <svg id="eye1" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-seg" id="s1"></div>
                        <div class="strength-seg" id="s2"></div>
                        <div class="strength-seg" id="s3"></div>
                        <div class="strength-seg" id="s4"></div>
                    </div>
                    <div class="strength-label" id="strengthLabel">Minimum 8 caractères, lettres et chiffres</div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-field">
                    <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="pass-wrap">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="••••••••"
                            autocomplete="new-password"
                            class="form-input"
                        />
                        <button type="button" class="pass-eye" onclick="togglePass('password_confirmation','eye2')" tabindex="-1">
                            <svg id="eye2" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Activer mon compte</button>
            </form>

        </div>
    </div>

    <div class="login-aside">
        <div class="aside-glow"></div>
        <div class="aside-logo">le<em>bon</em>véhicule</div>
        <p class="aside-tagline">La plateforme de vente de véhicules entre professionnels.</p>
    </div>
</div>

<script>
function togglePass(id, eyeId) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

function updateStrength(val) {
    const segs   = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
    const label  = document.getElementById('strengthLabel');
    const colors = ['#e53e3e', '#f97316', '#ecc94b', '#38a169'];
    const labels = ['Trop court', 'Faible', 'Moyen', 'Fort'];

    let score = 0;
    if (val.length >= 8)          score++;
    if (/[a-zA-Z]/.test(val))     score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^a-zA-Z0-9]/.test(val)) score++;

    segs.forEach((s, i) => {
        s.style.background = i < score ? colors[Math.max(0, score - 1)] : 'var(--border)';
    });

    label.textContent  = score > 0 ? labels[score - 1] : 'Minimum 8 caractères, lettres et chiffres';
    label.style.color  = score > 0 ? colors[score - 1] : 'var(--muted)';
}
</script>

</body>
</html>
