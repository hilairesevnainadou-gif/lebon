<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Vérification - Espace Vendeur</title>
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
            font-size: 30px;
            color: var(--text);
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
        }

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

        .form-input {
            width: 100%;
            height: 54px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 0 16px;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 8px;
            text-align: center;
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

        .btn-submit:hover {
            background: var(--orange-dark);
            transform: translateY(-1px);
        }

        .login-footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--orange);
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            padding: 0;
        }

        .btn-resend:hover { color: var(--orange-dark); }

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

        .alert-success {
            background: var(--green-light);
            border: 1px solid rgba(26,122,74,.25);
            border-left: 3px solid var(--green);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--green);
            font-weight: 600;
            margin-bottom: 24px;
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
            position: relative;
        }

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

    <div class="login-form-col">
        <div class="login-form-inner">

            <div class="login-logo">
                <span>le<em>bon</em>coin</span>
            </div>

            <h1 class="login-title">Vérification</h1>
            <p class="login-subtitle">Entrez le code à 6 chiffres envoyé par email</p>

            @if($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <div class="form-field">
                    <label class="form-label" for="code">Code de vérification</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        autofocus
                        autocomplete="one-time-code"
                        placeholder="------"
                        class="form-input @error('code') error @enderror"
                    />
                </div>

                <button type="submit" class="btn-submit">Vérifier</button>
            </form>

            <div class="login-footer">
                Vous n'avez rien reçu ?
                <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-resend">Renvoyer le code</button>
                </form>
            </div>

        </div>
    </div>

    <div class="login-aside">
        <div class="aside-glow"></div>
        <div class="aside-logo">le<em>bon</em>coin</div>
        <p class="aside-tagline">Une étape de vérification supplémentaire pour protéger votre compte.</p>
    </div>

</div>

</body>
</html>
