<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Activez votre compte</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f4f7;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2c2c3e;
        }
        .wrapper {
            max-width: 560px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.08);
        }
        .header {
            background: #1a1a2e;
            padding: 32px 40px 28px;
            text-align: center;
        }
        .header .logo {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -.5px;
        }
        .header .logo em {
            color: #f97316;
            font-style: normal;
        }
        .body {
            padding: 40px 40px 32px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
        }
        .text {
            font-size: 15px;
            color: #555575;
            line-height: 1.7;
            margin-bottom: 16px;
        }
        .btn-wrap {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            background: #f97316;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 10px;
            letter-spacing: .3px;
        }
        .notice {
            background: #f8f8fb;
            border: 1px solid #e8e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 13px;
            color: #888;
            line-height: 1.6;
            margin-top: 24px;
        }
        .notice a {
            color: #f97316;
            word-break: break-all;
        }
        .footer {
            border-top: 1px solid #f0f0f5;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">le<em>bon</em>véhicule</div>
    </div>

    <div class="body">
        <div class="greeting">Bonjour {{ $user->name }},</div>

        <p class="text">
            Un compte vous a été créé sur la plateforme <strong>{{ config('app.name') }}</strong>.
            Pour activer votre compte et définir votre mot de passe, cliquez sur le bouton ci-dessous.
        </p>
        <p class="text">
            Ce lien est valable <strong>72 heures</strong>. Passé ce délai, contactez un administrateur pour en recevoir un nouveau.
        </p>

        <div class="btn-wrap">
            <a href="{{ $activationUrl }}" class="btn">Activer mon compte</a>
        </div>

        <div class="notice">
            Si le bouton ne fonctionne pas, copiez-collez ce lien dans votre navigateur :<br/>
            <a href="{{ $activationUrl }}">{{ $activationUrl }}</a>
        </div>
    </div>

    <div class="footer">
        Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.
        &copy; {{ date('Y') }} {{ config('app.name') }}
    </div>
</div>
</body>
</html>
