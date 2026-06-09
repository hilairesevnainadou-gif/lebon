<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Connexion - Espace Vendeur</title>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        /* ---- Page : connexion ---- */

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

        /* ----- Colonne formulaire ----- */
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

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 30px;
            color: var(--text);
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
        }

        /* Champs formulaire */
        .form-field {
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

        .form-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .form-input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(229,90,0,.1);
        }

        .form-input.error {
            border-color: var(--red);
        }

        .form-forgot {
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .form-forgot:hover { color: var(--orange); }

        /* Checkbox remember */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            cursor: pointer;
        }

        .remember-box {
            width: 18px; height: 18px;
            border: 2px solid var(--border);
            border-radius: 5px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
            background: var(--card);
        }

        .remember-box.checked {
            background: var(--orange);
            border-color: var(--orange);
        }

        .remember-box.checked::after {
            content: '';
            display: block;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg) translateY(-1px);
        }

        .remember-label {
            font-size: 13px;
            color: var(--muted);
            user-select: none;
        }

        /* Bouton submit */
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
        }

        .btn-submit:hover {
            background: var(--orange-dark);
            transform: translateY(-1px);
        }

        /* Footer formulaire */
        .login-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        .login-footer a {
            color: var(--orange);
            font-weight: 600;
        }

        .login-footer a:hover { color: var(--orange-dark); }

        /* Alerte erreur */
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

        /* ----- Colonne droite (illustration) ----- */
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

        .aside-logo em {
            color: #ff7a35;
            font-style: normal;
        }

        .aside-tagline {
            font-size: 17px;
            color: rgba(255,255,255,.6);
            text-align: center;
            line-height: 1.6;
            max-width: 300px;
            margin-bottom: 40px;
            position: relative;
        }

        .aside-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            position: relative;
        }

        .aside-feature {
            display: flex;
            align-items: center;
            gap: 14px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 12px;
            padding: 14px 18px;
            color: rgba(255,255,255,.8);
            font-size: 14px;
            font-weight: 600;
        }

        .aside-feature-icon {
            width: 36px; height: 36px;
            background: rgba(255,122,53,.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .aside-feature-icon svg {
            width: 18px; height: 18px;
            stroke: #ff7a35;
            stroke-width: 1.8;
            fill: none;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-aside { display: none; }
        }

        @media (max-width: 480px) {
            .login-form-col { padding: 32px 20px; }
        }
    </style>
</head>
<body>

<div class="login-wrap">

    {{-- Formulaire --}}
    <div class="login-form-col">
        <div class="login-form-inner">

            <div class="login-logo">
                <span>le<em>bon</em>coin</span>
            </div>

            <h1 class="login-title">Connexion</h1>
            <p class="login-subtitle">Accédez à votre espace vendeur</p>

            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-field">
                    <label class="form-label" for="email">Adresse email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autofocus
                        autocomplete="email"
                        placeholder="nom@entreprise.com"
                        class="form-input @error('email') error @enderror"
                    />
                </div>

                <div class="form-field">
                    <div class="form-label-row">
                        <label class="form-label" for="password" style="margin-bottom:0;">Mot de passe</label>
                        <a href="{{ route('password.request') }}" class="form-forgot">Mot de passe oublié ?</a>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        class="form-input"
                    />
                </div>

                <div class="remember-row" onclick="toggleRemember()">
                    <div class="remember-box" id="rememberBox"></div>
                    <input type="checkbox" name="remember" id="rememberInput" style="display:none;"/>
                    <span class="remember-label">Se souvenir de moi</span>
                </div>

                <button type="submit" class="btn-submit">Se connecter</button>
            </form>

            <div class="login-footer">
                Pas encore de compte vendeur ?
                <a href="#">Nous contacter</a>
            </div>

        </div>
    </div>

    {{-- Illustration --}}
    <div class="login-aside">
        <div class="aside-glow"></div>
        <div class="aside-logo">le<em>bon</em>coin</div>
        <p class="aside-tagline">Gérez vos ventes, suivez vos performances et développez votre activité.</p>
        <div class="aside-features">
            <div class="aside-feature">
                <div class="aside-feature-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                </div>
                Gérez toutes vos annonces
            </div>
            <div class="aside-feature">
                <div class="aside-feature-icon">
                    <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                Suivez les vues en temps réel
            </div>
            <div class="aside-feature">
                <div class="aside-feature-icon">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                Finalisez vos transactions
            </div>
        </div>
    </div>

</div>

<script>
    function toggleRemember() {
        const box   = document.getElementById('rememberBox');
        const input = document.getElementById('rememberInput');
        input.checked = !input.checked;
        box.classList.toggle('checked', input.checked);
    }
</script>

</body>
</html>
