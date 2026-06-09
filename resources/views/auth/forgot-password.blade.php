<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Mot de passe oublié</title>
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

        .login-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        .login-footer a {
            color: var(--orange);
            font-weight: 600;
            text-decoration: none;
        }

        .login-footer a:hover { color: var(--orange-dark); }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid rgba(34,197,94,.25);
            border-left: 3px solid #22c55e;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 13px;
            color: #166534;
            font-weight: 600;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            margin-bottom: 28px;
            transition: color .15s;
        }

        .back-link:hover { color: var(--orange); }

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

        .icon-box {
            width: 56px; height: 56px;
            border-radius: 16px;
            background: var(--orange-lt);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
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
                <span>le<em>bon</em>coin</span>
            </div>

            <a href="{{ route('login') }}" class="back-link">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Retour à la connexion
            </a>

            <div class="icon-box">
                <svg width="24" height="24" fill="none" stroke="var(--orange)" stroke-width="1.8" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
            </div>

            <h1 class="login-title">Mot de passe oublié ?</h1>
            <p class="login-subtitle">
                Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>

            @if(session('success'))
                <div class="alert-success">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            @unless(session('success'))
                <form method="POST" action="{{ route('password.email') }}">
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
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        Envoyer le lien de réinitialisation
                    </button>
                </form>
            @endunless

            <div class="login-footer" style="margin-top:20px;">
                Vous vous souvenez de votre mot de passe ?
                <a href="{{ route('login') }}">Se connecter</a>
            </div>

        </div>
    </div>

    <div class="login-aside">
        <div class="aside-glow"></div>
        <div class="aside-logo">le<em>bon</em>coin</div>
        <p class="aside-tagline">Sécurisez votre compte et retrouvez l'accès à votre espace vendeur.</p>
    </div>
</div>

</body>
</html>
