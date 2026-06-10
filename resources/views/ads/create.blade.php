<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <title>Déposer une annonce - Espace Vendeur</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-size: 16px; scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #f7f6f3;
            color: #1c1c1c;
            min-height: 100vh;
        }
        input, select, textarea, button { font-family: inherit; }
        a { text-decoration: none; color: inherit; }

        :root {
            --orange: #e55a00;
            --orange-lt: #fff0e8;
            --navy: #0e1e3d;
            --green: #1a7a4a;
            --red: #d93025;
            --border: #e2e0da;
            --card: #ffffff;
            --bg: #f7f6f3;
            --text: #1c1c1c;
            --muted: #7a7870;
            --radius: 14px;
            --shadow: 0 2px 16px rgba(0,0,0,.07);
        }

        /* Layout */
        .wrapper { display: grid; grid-template-columns: 280px 1fr; min-height: 100vh; }

        /* Sidebar */
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
            padding: 0 28px 32px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            margin-bottom: 28px;
        }
        .sidebar-logo span {
            font-family: 'DM Serif Display', serif;
            font-size: 22px;
            color: #fff;
            letter-spacing: -.3px;
        }
        .sidebar-logo span em { color: #ff7a35; font-style: normal; }
        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.3);
            padding: 0 28px;
            margin-bottom: 10px;
        }
        .step-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 0 16px;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            transition: background .18s;
        }
        .step-item:hover { background: rgba(255,255,255,.06); }
        .step-item.active { background: rgba(255,121,53,.18); }
        .step-item.done .step-dot { background: var(--green); border-color: var(--green); }
        .step-item.done .step-dot::after {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .step-dot {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: rgba(255,255,255,.4);
            flex-shrink: 0;
            position: relative;
        }
        .step-item.active .step-dot { border-color: #ff7a35; background: #ff7a35; color: #fff; }
        .step-item.done .step-dot { color: transparent; }
        .step-num {
            font-size: 10px;
            color: rgba(255,255,255,.3);
            font-weight: 600;
        }
        .step-name {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,.5);
            line-height: 1.2;
        }
        .step-item.active .step-name { color: #fff; }
        .step-item.done .step-name { color: rgba(255,255,255,.7); }
        .step-connector {
            width: 2px;
            height: 12px;
            background: rgba(255,255,255,.1);
            margin: 0 27px;
            border-radius: 2px;
        }
        .step-connector.done { background: var(--green); }
        .sidebar-footer {
            padding: 20px 20px 0;
            border-top: 1px solid rgba(255,255,255,.08);
            margin-top: 16px;
        }
        .sidebar-progress {
            font-size: 12px;
            color: rgba(255,255,255,.4);
            margin-bottom: 8px;
        }
        .progress-bar {
            height: 3px;
            background: rgba(255,255,255,.1);
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff7a35, #ffaa70);
            border-radius: 10px;
            transition: width .4s;
        }

        /* Main */
        .main { display: flex; flex-direction: column; min-height: 100vh; }
        .topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
        }
        .topbar-breadcrumb a:hover { color: var(--orange); }
        .topbar-breadcrumb strong { color: var(--text); font-weight: 700; }
        .btn-save-draft {
            height: 36px;
            padding: 0 16px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            background: transparent;
            cursor: pointer;
            transition: .15s;
        }
        .btn-save-draft:hover { border-color: var(--orange); color: var(--orange); }

        .content {
            flex: 1;
            padding: 40px;
            max-width: 860px;
            width: 100%;
        }
        .step-page { display: none; animation: fadeUp .35s ease; }
        .step-page.active { display: block; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .step-header { margin-bottom: 32px; }
        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--orange-lt);
            color: var(--orange);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }
        .step-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            line-height: 1.2;
            color: var(--text);
            margin-bottom: 8px;
        }
        .step-subtitle {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.5;
        }
        .form-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }
        .form-card-title {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-card-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .col-2 { grid-column: span 2; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field label {
            font-size: 12px;
            font-weight: 700;
            color: #555;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .field label .req { color: var(--orange); }
        .field input, .field select, .field textarea {
            height: 44px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            font-size: 14px;
            color: var(--text);
            background: #fff;
            outline: none;
            transition: all .15s;
        }
        .field textarea {
            height: auto;
            padding: 12px 14px;
            resize: vertical;
            line-height: 1.5;
            min-height: 120px;
        }
        .field select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23999' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(229,90,0,.12);
        }
        .field .error-msg {
            font-size: 11px;
            color: var(--red);
            font-weight: 600;
            margin-top: 4px;
        }
        .input-group {
            display: flex;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }
        .input-group:focus-within {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(229,90,0,.12);
        }
        .input-group-prefix {
            height: 44px;
            padding: 0 14px;
            background: #f7f6f3;
            display: flex;
            align-items: center;
            font-size: 14px;
            color: var(--muted);
            font-weight: 600;
            border-right: 1.5px solid var(--border);
            flex-shrink: 0;
        }
        .input-group input {
            flex: 1;
            border: none !important;
            border-radius: 0 !important;
            height: 44px;
        }
        .radio-cards {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .radio-card { position: relative; }
        .radio-card input { position: absolute; opacity: 0; width: 0; height: 0; }
        .radio-card label {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 40px;
            padding: 0 18px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            cursor: pointer;
            transition: all .15s;
        }
        .radio-card input:checked + label {
            border-color: var(--orange);
            background: var(--orange-lt);
            color: var(--orange);
        }
        .equip-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .equip-check { position: relative; }
        .equip-check input { position: absolute; opacity: 0; width: 0; height: 0; }
        .equip-check label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            cursor: pointer;
            transition: all .15s;
        }
        .equip-check label .ec-box {
            width: 18px;
            height: 18px;
            border: 2px solid #d0cec8;
            border-radius: 5px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .15s;
        }
        .equip-check input:checked + label {
            border-color: var(--orange);
            background: var(--orange-lt);
            color: var(--orange);
        }
        .equip-check input:checked + label .ec-box {
            background: var(--orange);
            border-color: var(--orange);
        }
        .equip-check input:checked + label .ec-box::after {
            content: '';
            display: block;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }
        .photo-zone {
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
            background: #fafaf8;
        }
        .photo-zone:hover, .photo-zone.dragover {
            border-color: var(--orange);
            background: var(--orange-lt);
        }
        .photo-zone-icon {
            width: 56px;
            height: 56px;
            background: #eeece8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }
        .photo-zone-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }
        .photo-zone-sub { font-size: 13px; color: var(--muted); }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 20px;
        }
        .photo-thumb {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            background: #eee;
            border: 2px solid transparent;
            cursor: grab;
            transition: opacity .2s, transform .15s, border-color .15s;
        }
        .photo-thumb:first-child { border-color: var(--orange); }
        .photo-thumb.dragging { opacity: .3; cursor: grabbing; }
        .photo-thumb.drag-over { border-color: var(--orange) !important; transform: scale(1.04); }
        .photo-thumb img { width: 100%; height: 100%; object-fit: cover; pointer-events: none; }
        .photo-thumb .thumb-del {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(0,0,0,.6);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .15s;
            color: white;
        }
        .photo-thumb:hover .thumb-del { opacity: 1; }
        .photo-thumb .thumb-pin {
            position: absolute;
            top: 6px;
            left: 6px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(14,30,61,.75);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .15s;
            z-index: 2;
        }
        .photo-thumb:hover .thumb-pin { opacity: 1; }
        .photo-thumb .thumb-main-badge {
            position: absolute;
            bottom: 6px;
            left: 6px;
            background: var(--orange);
            color: #fff;
            font-size: 9px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 20px;
        }
        .photo-count-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--muted);
            margin-top: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }
        .step-nav-btns {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn-prev {
            height: 46px;
            padding: 0 24px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: transparent;
            font-size: 14px;
            font-weight: 700;
            color: var(--muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-prev:hover { border-color: var(--orange); color: var(--orange); }
        .btn-prev:disabled { opacity: .4; cursor: not-allowed; }
        .btn-next {
            height: 46px;
            padding: 0 28px;
            border-radius: 10px;
            background: var(--orange);
            border: none;
            font-size: 14px;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(229,90,0,.3);
        }
        .btn-next:hover { background: #cc5000; }
        .btn-publish {
            height: 52px;
            padding: 0 36px;
            border-radius: 12px;
            background: var(--green);
            border: none;
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 20px rgba(26,122,74,.3);
        }
        .btn-publish:hover { background: #155f3a; }
        .recap-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .recap-card {
            background: #fafaf8;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
        }
        .recap-card-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }
        .recap-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            padding: 6px 0;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 8px;
        }
        .recap-row:last-child { border-bottom: none; }
        .recap-row span:first-child { color: var(--muted); }
        .recap-row span:last-child { font-weight: 700; color: var(--text); text-align: right; }
        .recap-photos {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }
        .recap-photo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
        }
        .recap-features {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }
        .recap-tag {
            background: #eeece8;
            color: #555;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }
        .alert-error {
            background: #fff2f0;
            border: 1px solid #ffc9c0;
            color: var(--red);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }
        .alert-error.show { display: flex; }
        .info-box {
            background: #f0f7ff;
            border: 1px solid #cce0ff;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 12px;
            color: #2060a0;
            line-height: 1.5;
            margin-top: 12px;
            display: flex;
            gap: 10px;
        }

        /* Flash messages flottants */
        .toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
        }
        .toast.success {
            background: #e8f5ef;
            border-left: 4px solid var(--green);
            color: var(--green);
        }
        .toast.error {
            background: #fff2f0;
            border-left: 4px solid var(--red);
            color: var(--red);
        }
        .toast.info {
            background: #f0f7ff;
            border-left: 4px solid #2060a0;
            color: #2060a0;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Modal */
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
        .modal.open { display: flex; animation: fadeIn 0.2s ease; }
        .modal-content {
            background: var(--card);
            border-radius: 20px;
            max-width: 420px;
            width: 90%;
            padding: 28px;
            text-align: center;
            animation: slideUp 0.3s ease;
        }
        .modal-icon {
            width: 64px;
            height: 64px;
            background: #e8f5ef;
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .modal-icon svg { width: 32px; height: 32px; stroke: var(--green); stroke-width: 1.8; }
        .modal-title { font-size: 22px; font-weight: 800; color: var(--text); margin-bottom: 12px; }
        .modal-text { font-size: 14px; color: var(--muted); margin-bottom: 24px; line-height: 1.5; }
        .modal-buttons { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .modal-btn {
            padding: 10px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .modal-btn-cancel { background: var(--bg); border: 1px solid var(--border); color: var(--text); }
        .modal-btn-cancel:hover { background: var(--border); }
        .modal-btn-confirm { background: var(--green); color: white; }
        .modal-btn-confirm:hover { background: #155f3a; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile */
        .mobile-steps {
            display: none;
            gap: 4px;
            padding: 14px 20px;
            background: var(--card);
            border-bottom: 1px solid var(--border);
        }
        .mobile-step-dot {
            flex: 1;
            height: 3px;
            background: var(--border);
            border-radius: 10px;
            transition: background .3s;
        }
        .mobile-step-dot.active { background: var(--orange); }
        .mobile-step-dot.done { background: var(--green); }

        @media (max-width: 992px) {
            .grid-3 { grid-template-columns: 1fr 1fr; }
            .equip-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .wrapper { grid-template-columns: 1fr; }
            .sidebar { display: none; }
            .mobile-steps { display: flex; }
            .content { padding: 20px; max-width: 100%; }
            .topbar { padding: 0 16px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .col-2 { grid-column: span 1; }
            .photo-grid { grid-template-columns: repeat(3, 1fr); }
            .recap-grid { grid-template-columns: 1fr; }
            .step-title { font-size: 24px; }
            .form-card { padding: 20px; }
            .recap-row span:last-child { text-align: left; width: 100%; }
            .toast { left: 20px; right: 20px; max-width: none; top: 70px; }
        }

        @media (max-width: 480px) {
            .photo-grid { grid-template-columns: repeat(2, 1fr); }
            .equip-grid { grid-template-columns: 1fr; }
            .btn-prev, .btn-next, .btn-publish { width: 100%; justify-content: center; }
            .step-nav-btns { flex-direction: column-reverse; }
        }
    </style>
</head>
<body>

<form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data" id="adForm">
@csrf
@if(isset($draftData) && is_array($draftData) && isset($draftData['id']))
    <input type="hidden" name="draft_id" value="{{ $draftData['id'] }}">
@endif

<div class="wrapper">
    <aside class="sidebar">
        <div class="sidebar-logo"><span>le<em>bon</em>coin</span></div>
        <div class="sidebar-label">Etapes</div>
        <div class="step-nav" id="stepNav"></div>
        <div class="sidebar-footer">
            <div class="sidebar-progress">Progression : <span id="progressPct">0</span>%</div>
            <div class="progress-bar"><div class="progress-fill" id="progressFill" style="width:0%"></div></div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div class="topbar-breadcrumb">
                <a href="{{ route('ads.index') }}">Mes annonces</a>
                <span>›</span>
                <strong>Nouvelle annonce</strong>
            </div>
            <button type="button" class="btn-save-draft" id="saveDraftBtn">Sauvegarder brouillon</button>
        </div>

        <div class="mobile-steps" id="mobileDots"></div>

        <div class="content">
            @if(session('error'))
                <div class="toast error" id="sessionError">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="toast error" id="validationErrors">
                    @foreach($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- STEP 1: VENDEUR -->
            <div class="step-page active" data-step="1">
                <div class="step-header">
                    <div class="step-badge">Etape 1 / 6</div>
                    <h1 class="step-title">Qui etes-vous ?</h1>
                    <p class="step-subtitle">Ces informations identifient le vendeur.</p>
                </div>
                <div class="alert-error" id="err1"><span id="err1msg">Veuillez corriger les erreurs.</span></div>
                <div class="form-card">
                    <div class="form-card-title">Identite</div>
                    <div class="grid-2">
                        <div class="field">
                            <label>Prenom <span class="req">*</span></label>
                            <input type="text" name="seller[first_name]" id="s_fname" value="{{ old('seller.first_name', auth()->user()->name ?? '') }}" placeholder="Jean">
                            @error('seller.first_name')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Nom <span class="req">*</span></label>
                            <input type="text" name="seller[last_name]" id="s_lname" value="{{ old('seller.last_name') }}" placeholder="Dupont">
                            @error('seller.last_name')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Email <span class="req">*</span></label>
                            <input type="email" name="seller[email]" id="s_email" value="{{ old('seller.email', auth()->user()->email ?? '') }}" placeholder="jean@email.com">
                            @error('seller.email')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Telephone <span class="req">*</span></label>
                            <input type="tel" name="seller[phone]" id="s_phone" value="{{ old('seller.phone') }}" placeholder="06 12 34 56 78">
                            @error('seller.phone')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field col-2">
                            <label>Ville <span class="req">*</span></label>
                            <input type="text" name="seller[city]" id="s_city" value="{{ old('seller.city') }}" placeholder="Besancon">
                            @error('seller.city')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" disabled>Precedent</button>
                    <button type="button" class="btn-next" onclick="goNext(1)">Continuer</button>
                </div>
            </div>

            <!-- STEP 2: BANQUE -->
            <div class="step-page" data-step="2">
                <div class="step-header">
                    <div class="step-badge">Etape 2 / 6</div>
                    <h1 class="step-title">Coordonnees bancaires</h1>
                    <p class="step-subtitle">Pour recevoir le paiement lors de la vente.</p>
                </div>
                <div class="alert-error" id="err2"><span id="err2msg">Veuillez corriger les erreurs.</span></div>
                <div class="form-card">
                    <div class="form-card-title">Compte bancaire</div>
                    <div class="grid-2">
                        <div class="field col-2">
                            <label>Titulaire <span class="req">*</span></label>
                            <input type="text" name="bank[account_holder_name]" id="b_holder" value="{{ old('bank.account_holder_name') }}" placeholder="Jean Dupont">
                            @error('bank.account_holder_name')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field col-2">
                            <label>IBAN <span class="req">*</span></label>
                            <input type="text" name="bank[iban]" id="b_iban" value="{{ old('bank.iban') }}" placeholder="FR76 3000 6000 0112 3456 7890 189">
                            @error('bank.iban')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>BIC <span class="req">*</span></label>
                            <input type="text" name="bank[bic]" id="b_bic" value="{{ old('bank.bic') }}" placeholder="BNPAFRPP">
                            @error('bank.bic')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Banque</label>
                            <input type="text" name="bank[bank_name]" value="{{ old('bank.bank_name') }}" placeholder="BNP Paribas">
                        </div>
                    </div>
                    <div class="info-box">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                        Vos coordonnees bancaires sont chiffrees et jamais transmises aux acheteurs.
                    </div>
                </div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" onclick="goStep(1)">Precedent</button>
                    <button type="button" class="btn-next" onclick="goNext(2)">Continuer</button>
                </div>
            </div>

            <!-- STEP 3: ANNONCE -->
            <div class="step-page" data-step="3">
                <div class="step-header">
                    <div class="step-badge">Etape 3 / 6</div>
                    <h1 class="step-title">L'annonce</h1>
                    <p class="step-subtitle">Titre accrocheur, description detaillee et prix juste.</p>
                </div>
                <div class="alert-error" id="err3"><span id="err3msg">Veuillez corriger les erreurs.</span></div>
                <div class="form-card">
                    <div class="form-card-title">Informations generales</div>
                    <div class="grid-2">
                        <div class="field col-2">
                            <label>Titre <span class="req">*</span></label>
                            <input type="text" name="ad[title]" id="a_title" value="{{ old('ad.title') }}" placeholder="Ex: Audi A5 3.0 TDI 245ch">
                            @error('ad.title')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Prix <span class="req">*</span></label>
                            <div class="input-group">
                                <span class="input-group-prefix">EUR</span>
                                <input type="number" name="ad[price]" id="a_price" value="{{ old('ad.price') }}" placeholder="12900" min="0">
                            </div>
                            @error('ad.price')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Ville <span class="req">*</span></label>
                            <input type="text" name="ad[city]" id="a_city" value="{{ old('ad.city') }}" placeholder="Besancon">
                            @error('ad.city')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Code postal</label>
                            <input type="text" name="ad[postal_code]" id="a_postal" value="{{ old('ad.postal_code') }}" placeholder="25000" maxlength="10">
                            @error('ad.postal_code')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Nombre de favoris</label>
                            <input type="number" name="ad[likes_count]" id="a_likes" value="{{ old('ad.likes_count', 0) }}" min="0" placeholder="0">
                            @error('ad.likes_count')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field col-2">
                            <label>Description</label>
                            <textarea name="ad[description]" rows="5" placeholder="Decrivez l'etat general, l'historique d'entretien...">{{ old('ad.description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" onclick="goStep(2)">Precedent</button>
                    <button type="button" class="btn-next" onclick="goNext(3)">Continuer</button>
                </div>
            </div>

            <!-- STEP 4: VEHICULE -->
            <div class="step-page" data-step="4">
                <div class="step-header">
                    <div class="step-badge">Etape 4 / 6</div>
                    <h1 class="step-title">Details du vehicule</h1>
                    <p class="step-subtitle">Plus vous etes precis, plus les acheteurs serieux vous contactent.</p>
                </div>
                <div class="alert-error" id="err4"><span id="err4msg">Veuillez corriger les erreurs.</span></div>
                <div class="form-card">
                    <div class="form-card-title">Identification</div>
                    <div class="grid-3">
                        <div class="field">
                            <label>Marque <span class="req">*</span></label>
                            <input type="text" name="vehicle[brand]" id="v_brand" value="{{ old('vehicle.brand') }}" placeholder="Audi">
                            @error('vehicle.brand')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Modele <span class="req">*</span></label>
                            <input type="text" name="vehicle[model]" id="v_model" value="{{ old('vehicle.model') }}" placeholder="A5">
                            @error('vehicle.model')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Annee <span class="req">*</span></label>
                            <input type="number" name="vehicle[year]" id="v_year" value="{{ old('vehicle.year') }}" placeholder="2020" min="1900" max="{{ date('Y') }}">
                            @error('vehicle.year')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="form-card">
                    <div class="form-card-title">Caracteristiques</div>
                    <div class="grid-2">
                        <div class="field">
                            <label>Kilometrage <span class="req">*</span></label>
                            <div class="input-group">
                                <input type="number" name="vehicle[mileage]" id="v_mileage" value="{{ old('vehicle.mileage') }}" placeholder="120000" min="0">
                                <span class="input-group-prefix">km</span>
                            </div>
                            @error('vehicle.mileage')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>1ere mise en circulation</label>
                            <input type="date" name="vehicle[first_registration_date]" value="{{ old('vehicle.first_registration_date') }}">
                        </div>
                    </div>
                    <div class="field">
                        <label>Carburant <span class="req">*</span></label>
                        <div class="radio-cards">
                            @foreach(['diesel'=>'Diesel','essence'=>'Essence','hybride'=>'Hybride','electrique'=>'Electrique','gpl'=>'GPL'] as $val=>$label)
                            <div class="radio-card">
                                <input type="radio" name="vehicle[fuel_type]" id="fuel_{{ $val }}" value="{{ $val }}" {{ old('vehicle.fuel_type') === $val ? 'checked' : '' }}>
                                <label for="fuel_{{ $val }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('vehicle.fuel_type')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label>Boite <span class="req">*</span></label>
                        <div class="radio-cards">
                            <div class="radio-card">
                                <input type="radio" name="vehicle[gearbox]" id="gb_man" value="manuelle" {{ old('vehicle.gearbox') === 'manuelle' ? 'checked' : '' }}>
                                <label for="gb_man">Manuelle</label>
                            </div>
                            <div class="radio-card">
                                <input type="radio" name="vehicle[gearbox]" id="gb_auto" value="automatique" {{ old('vehicle.gearbox') === 'automatique' ? 'checked' : '' }}>
                                <label for="gb_auto">Automatique</label>
                            </div>
                        </div>
                        @error('vehicle.gearbox')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-card">
                    <div class="form-card-title">Details supplementaires</div>
                    <div class="grid-3">
                        <div class="field">
                            <label>Nombre de portes</label>
                            <input type="number" name="vehicle[doors]" id="v_doors" value="{{ old('vehicle.doors') }}" min="1" max="9" placeholder="5">
                        </div>
                        <div class="field">
                            <label>Nombre de places</label>
                            <input type="number" name="vehicle[seats]" id="v_seats" value="{{ old('vehicle.seats') }}" min="1" max="20" placeholder="5">
                        </div>
                        <div class="field">
                            <label>Type de vehicule</label>
                            <select name="vehicle[body_type]" id="v_body_type">
                                <option value="">Selectionner</option>
                                @foreach(['Berline','SUV','Coupe','Cabriolet','Break','Citadine','Monospace','Utilitaire','Pickup'] as $bt)
                                <option value="{{ $bt }}" {{ old('vehicle.body_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Finition Constructeur</label>
                            <input type="text" name="vehicle[finish]" id="v_finish" value="{{ old('vehicle.finish') }}" placeholder="Pack M, Executive...">
                        </div>
                        <div class="field">
                            <label>Version Constructeur</label>
                            <input type="text" name="vehicle[version]" id="v_version" value="{{ old('vehicle.version') }}" placeholder="xDrive, AMG Line...">
                        </div>
                        <div class="field">
                            <label>Etat du vehicule</label>
                            <select name="vehicle[condition]" id="v_condition">
                                <option value="">Selectionner</option>
                                @foreach(['Excellent','Très bon','Bon','Correct','À réparer'] as $c)
                                <option value="{{ $c }}" {{ old('vehicle.condition') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Sellerie</label>
                            <input type="text" name="vehicle[upholstery]" id="v_upholstery" value="{{ old('vehicle.upholstery') }}" placeholder="Cuir, Tissu...">
                        </div>
                        <div class="field">
                            <label>Historique et entretien</label>
                            <input type="text" name="vehicle[history]" id="v_history" value="{{ old('vehicle.history') }}" placeholder="Carnet entretien, 1 seul proprietaire...">
                        </div>
                        <div class="field">
                            <label>Permis requis</label>
                            <input type="text" name="vehicle[license]" id="v_license" value="{{ old('vehicle.license') }}" placeholder="B, A, A2...">
                        </div>
                        <div class="field">
                            <label>Couleur</label>
                            <input type="text" name="vehicle[color]" id="v_color" value="{{ old('vehicle.color') }}" placeholder="Noir, Blanc...">
                        </div>
                        <div class="field">
                            <label>Crit'Air</label>
                            <select name="vehicle[critair]" id="v_critair">
                                <option value="">Selectionner</option>
                                @foreach([0=>"0 (Electrique)",1=>"1",2=>"2",3=>"3",4=>"4",5=>"5"] as $cv => $cl)
                                <option value="{{ $cv }}" {{ old('vehicle.critair') == $cv ? 'selected' : '' }}>{{ $cl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Puissance fiscale</label>
                            <div class="input-group">
                                <input type="number" name="vehicle[fiscal_power]" id="v_fiscal_power" value="{{ old('vehicle.fiscal_power') }}" placeholder="14" min="0">
                                <span class="input-group-prefix">CV</span>
                            </div>
                        </div>
                        <div class="field">
                            <label>Puissance DIN</label>
                            <div class="input-group">
                                <input type="number" name="vehicle[din_power]" id="v_din_power" value="{{ old('vehicle.din_power') }}" placeholder="245" min="0">
                                <span class="input-group-prefix">ch</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" onclick="goStep(3)">Precedent</button>
                    <button type="button" class="btn-next" onclick="goNext(4)">Continuer</button>
                </div>
            </div>

            <!-- STEP 5: EQUIPEMENTS + PHOTOS -->
            <div class="step-page" data-step="5">
                <div class="step-header">
                    <div class="step-badge">Etape 5 / 6</div>
                    <h1 class="step-title">Equipements & Photos</h1>
                    <p class="step-subtitle">Ajoutez au minimum 1 photo (12 maximum).</p>
                </div>
                <div class="alert-error" id="err5"><span id="err5msg">Ajoutez au moins une photo.</span></div>
                <div class="form-card">
                    <div class="form-card-title">Equipements</div>
                    @php
                        $features = [
                            'Toit ouvrant', 'Camera de recul', 'Regulateur de vitesse',
                            'Limiteur de vitesse', 'Sieges chauffants', 'Climatisation auto',
                            'GPS', 'Bluetooth', 'Jantes alliage', 'Xenons / LED',
                            'Demarrage sans cle', 'Vitres electriques', 'Retros electriques',
                            'Attelage', 'Aide au stationnement', 'Volant chauffant'
                        ];
                    @endphp
                    <div class="equip-grid">
                        @foreach($features as $feat)
                        <div class="equip-check">
                            <input type="checkbox" name="features[]" id="feat_{{ $loop->index }}" value="{{ $feat }}" {{ in_array($feat, old('features', [])) ? 'checked' : '' }}>
                            <label for="feat_{{ $loop->index }}"><span class="ec-box"></span>{{ $feat }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-card">
                    <div class="form-card-title">Photos</div>
                    <div class="photo-zone" id="dropZone" onclick="document.getElementById('photoInput').click()">
                        <div class="photo-zone-icon">
                            <svg width="24" height="24" fill="none" stroke="#888" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                                <circle cx="12" cy="13" r="4"/>
                            </svg>
                        </div>
                        <div class="photo-zone-title">Glissez vos photos ici</div>
                        <div class="photo-zone-sub">ou cliquez pour parcourir · JPG, PNG, WEBP · max 5 Mo</div>
                        <input type="file" name="photos[]" id="photoInput" multiple accept="image/jpeg,image/png,image/webp" style="display:none">
                    </div>
                    <div class="photo-grid" id="photoGrid"></div>
                    <div class="photo-count-bar">
                        <span>La 1ere photo sera la photo principale</span>
                        <span><strong id="photoCountNum">0</strong> / 12</span>
                    </div>
                    @error('photos')<span class="error-msg">{{ $message }}</span>@enderror
                    @error('photos.*')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" onclick="goStep(4)">Precedent</button>
                    <button type="button" class="btn-next" onclick="goNext(5)">Verifier l'annonce</button>
                </div>
            </div>

            <!-- STEP 6: RECAPITULATIF -->
            <div class="step-page" data-step="6">
                <div class="step-header">
                    <div class="step-badge">Etape 6 / 6</div>
                    <h1 class="step-title">Recapitulatif</h1>
                    <p class="step-subtitle">Verifiez toutes les informations avant de publier.</p>
                </div>
                <div class="recap-grid" id="recapGrid"></div>
                <div class="step-nav-btns">
                    <button type="button" class="btn-prev" onclick="goStep(5)">Modifier</button>
                    <button type="submit" class="btn-publish">Publier l'annonce</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<!-- MODAL BROUILLON -->
<div class="modal" id="draftModal">
    <div class="modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="modal-title">Enregistrer le brouillon</div>
        <div class="modal-text">Votre brouillon sera sauvegarde. Vous pourrez le retrouver plus tard dans votre espace vendeur.</div>
        <div class="modal-buttons">
            <button class="modal-btn modal-btn-cancel" onclick="closeDraftModal()">Annuler</button>
            <button class="modal-btn modal-btn-confirm" id="confirmDraftBtn">Enregistrer</button>
        </div>
    </div>
</div>

<script>
// Configuration des etapes
const STEPS = [
    { id: 1, name: 'Vendeur' }, { id: 2, name: 'Banque' }, { id: 3, name: 'Annonce' },
    { id: 4, name: 'Vehicule' }, { id: 5, name: 'Photos' }, { id: 6, name: 'Recap' }
];
let currentStep = 1;
const doneSteps = new Set();

// Navigation
function buildNav() {
    const nav = document.getElementById('stepNav');
    const dots = document.getElementById('mobileDots');
    if (!nav) return;
    nav.innerHTML = '';
    dots.innerHTML = '';

    STEPS.forEach((s, i) => {
        if (i > 0) {
            const conn = document.createElement('div');
            conn.className = 'step-connector' + (doneSteps.has(i) ? ' done' : '');
            nav.appendChild(conn);
        }
        const item = document.createElement('div');
        item.className = 'step-item' + (s.id === currentStep ? ' active' : '') + (doneSteps.has(s.id) ? ' done' : '');
        item.onclick = () => { if (doneSteps.has(s.id) || s.id <= currentStep) goStep(s.id); };
        item.innerHTML = `<div class="step-dot">${doneSteps.has(s.id) ? '' : s.id}</div><div class="step-text"><div class="step-num">Etape ${s.id}</div><div class="step-name">${s.name}</div></div>`;
        nav.appendChild(item);

        const dot = document.createElement('div');
        dot.className = 'mobile-step-dot' + (s.id === currentStep ? ' active' : '') + (doneSteps.has(s.id) ? ' done' : '');
        dots.appendChild(dot);
    });

    const pct = Math.round((doneSteps.size / STEPS.length) * 100);
    const progressPct = document.getElementById('progressPct');
    const progressFill = document.getElementById('progressFill');
    if (progressPct) progressPct.textContent = pct;
    if (progressFill) progressFill.style.width = pct + '%';
}

function goStep(n) {
    const currentPage = document.querySelector(`.step-page[data-step="${currentStep}"]`);
    const nextPage = document.querySelector(`.step-page[data-step="${n}"]`);
    if (currentPage) currentPage.classList.remove('active');
    if (nextPage) nextPage.classList.add('active');
    currentStep = n;
    buildNav();
    if (n === 6) buildRecap();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Validation
function validateStep(step) {
    let errors = [];

    if (step === 1) {
        if (!document.getElementById('s_fname')?.value.trim()) errors.push('Prenom requis');
        if (!document.getElementById('s_lname')?.value.trim()) errors.push('Nom requis');
        const email = document.getElementById('s_email')?.value.trim();
        if (!email) errors.push('Email requis');
        else if (!/\S+@\S+\.\S+/.test(email)) errors.push('Email invalide');
        if (!document.getElementById('s_phone')?.value.trim()) errors.push('Telephone requis');
        if (!document.getElementById('s_city')?.value.trim()) errors.push('Ville requise');
    } else if (step === 2) {
        if (!document.getElementById('b_holder')?.value.trim()) errors.push('Titulaire requis');
        if (!document.getElementById('b_iban')?.value.trim()) errors.push('IBAN requis');
        if (!document.getElementById('b_bic')?.value.trim()) errors.push('BIC requis');
    } else if (step === 3) {
        if (!document.getElementById('a_title')?.value.trim()) errors.push('Titre requis');
        if (!document.getElementById('a_price')?.value.trim()) errors.push('Prix requis');
        if (!document.getElementById('a_city')?.value.trim()) errors.push('Ville requise');
    } else if (step === 4) {
        if (!document.getElementById('v_brand')?.value.trim()) errors.push('Marque requise');
        if (!document.getElementById('v_model')?.value.trim()) errors.push('Modele requis');
        if (!document.getElementById('v_year')?.value.trim()) errors.push('Annee requise');
        if (!document.getElementById('v_mileage')?.value.trim()) errors.push('Kilometrage requis');
        if (!document.querySelector('input[name="vehicle[fuel_type]"]:checked')) errors.push('Carburant requis');
        if (!document.querySelector('input[name="vehicle[gearbox]"]:checked')) errors.push('Boite requise');
    } else if (step === 5) {
        if (files.length === 0) errors.push('Au moins une photo est requise');
    }

    const alertEl = document.getElementById(`err${step}`);
    if (errors.length) {
        if (alertEl) {
            alertEl.classList.add('show');
            const msgSpan = document.getElementById(`err${step}msg`);
            if (msgSpan) msgSpan.textContent = errors[0];
            alertEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return false;
    }
    if (alertEl) alertEl.classList.remove('show');
    return true;
}

function goNext(step) {
    if (!validateStep(step)) return;
    doneSteps.add(step);
    goStep(step + 1);
}

// Gestion des photos
let files = [];
const dropZone = document.getElementById('dropZone');
const photoInput = document.getElementById('photoInput');
const grid = document.getElementById('photoGrid');
const countEl = document.getElementById('photoCountNum');

if (dropZone) {
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
    dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
    dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('dragover'); addFiles([...e.dataTransfer.files]); });
}
if (photoInput) {
    photoInput.addEventListener('change', () => {
        const selected = [...photoInput.files]; // copie avant effacement
        photoInput.value = '';                   // vider pour permettre re-sélection du même fichier
        addFiles(selected);
    });
}

// Synchroniser les fichiers juste avant la soumission du formulaire
document.getElementById('adForm')?.addEventListener('submit', () => {
    syncFileInput();
});

function addFiles(newFiles) {
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];
    for (const f of newFiles) {
        if (!allowed.includes(f.type)) { showToast(f.name + ' : format non accepte', 'error'); continue; }
        if (f.size > 5 * 1024 * 1024) { showToast(f.name + ' depasse 5 Mo', 'error'); continue; }
        if (files.length >= 12) { showToast('Maximum 12 photos', 'error'); break; }
        files.push(f);
    }
    renderPhotos();
    syncFileInput();
}

function removeFile(i) { files.splice(i, 1); renderPhotos(); syncFileInput(); }

function moveFile(from, to) {
    const item = files.splice(from, 1)[0];
    files.splice(to, 0, item);
    renderPhotos();
    syncFileInput();
}

let dragSrcIdx = null;

function renderPhotos() {
    if (!grid) return;
    // Libérer les anciens ObjectURL pour éviter les fuites mémoire
    grid.querySelectorAll('img').forEach(img => URL.revokeObjectURL(img.src));
    grid.innerHTML = '';
    files.forEach((f, i) => {
        const url = URL.createObjectURL(f);
        const div = document.createElement('div');
        div.className = 'photo-thumb';
        div.draggable = true;
        div.dataset.idx = i;
        div.innerHTML = `
            <img src="${url}" alt="Photo ${i+1}">
            <button type="button" class="thumb-del" onclick="removeFile(${i})" title="Supprimer">
                <svg width="12" height="12" fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
            ${i === 0
                ? '<div class="thumb-main-badge">Principale</div>'
                : `<button type="button" class="thumb-pin" onclick="moveFile(${i},0)" title="Mettre en 1ère position">
                       <svg width="11" height="11" fill="white" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/></svg>
                   </button>`
            }
        `;
        // Drag events
        div.addEventListener('dragstart', e => {
            dragSrcIdx = i;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => div.classList.add('dragging'), 0);
        });
        div.addEventListener('dragend', () => {
            div.classList.remove('dragging');
            grid.querySelectorAll('.photo-thumb').forEach(el => el.classList.remove('drag-over'));
            dragSrcIdx = null;
        });
        div.addEventListener('dragover', e => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (dragSrcIdx !== null && dragSrcIdx !== i) {
                grid.querySelectorAll('.photo-thumb').forEach(el => el.classList.remove('drag-over'));
                div.classList.add('drag-over');
            }
        });
        div.addEventListener('dragleave', () => div.classList.remove('drag-over'));
        div.addEventListener('drop', e => {
            e.preventDefault();
            div.classList.remove('drag-over');
            if (dragSrcIdx !== null && dragSrcIdx !== i) {
                moveFile(dragSrcIdx, i);
            }
        });
        grid.appendChild(div);
    });
    if (countEl) countEl.textContent = files.length;
}

function syncFileInput() {
    if (!photoInput) return;
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    photoInput.files = dt.files;
}

// Recapitulatif
function buildRecap() {
    const recap = document.getElementById('recapGrid');
    if (!recap) return;

    const v = id => document.getElementById(id)?.value || '—';
    const fuel = document.querySelector('input[name="vehicle[fuel_type]"]:checked')?.value || '—';
    const gearbox = document.querySelector('input[name="vehicle[gearbox]"]:checked')?.value || '—';
    const feats = [...document.querySelectorAll('input[name="features[]"]:checked')].map(e => e.value);

    recap.innerHTML = `
        <div class="recap-card"><div class="recap-card-title">Vendeur</div>
            <div class="recap-row"><span>Prenom / Nom</span><span>${escapeHtml(v('s_fname'))} ${escapeHtml(v('s_lname'))}</span></div>
            <div class="recap-row"><span>Email</span><span>${escapeHtml(v('s_email'))}</span></div>
            <div class="recap-row"><span>Telephone</span><span>${escapeHtml(v('s_phone'))}</span></div>
            <div class="recap-row"><span>Ville</span><span>${escapeHtml(v('s_city'))}</span></div>
        </div>
        <div class="recap-card"><div class="recap-card-title">Annonce</div>
            <div class="recap-row"><span>Titre</span><span>${escapeHtml(v('a_title'))}</span></div>
            <div class="recap-row"><span>Prix</span><span>${v('a_price') !== '—' ? Number(v('a_price')).toLocaleString('fr-FR') + ' EUR' : '—'}</span></div>
            <div class="recap-row"><span>Ville</span><span>${escapeHtml(v('a_city'))}</span></div>
        </div>
        <div class="recap-card"><div class="recap-card-title">Vehicule</div>
            <div class="recap-row"><span>Marque / Modele</span><span>${escapeHtml(v('v_brand'))} ${escapeHtml(v('v_model'))}</span></div>
            <div class="recap-row"><span>Annee</span><span>${escapeHtml(v('v_year'))}</span></div>
            <div class="recap-row"><span>Kilometrage</span><span>${v('v_mileage') !== '—' ? Number(v('v_mileage')).toLocaleString('fr-FR') + ' km' : '—'}</span></div>
            <div class="recap-row"><span>Carburant / Boite</span><span>${escapeHtml(fuel)} / ${escapeHtml(gearbox)}</span></div>
            <div class="recap-row"><span>Portes / Places</span><span>${escapeHtml(v('v_doors'))} / ${escapeHtml(v('v_seats'))}</span></div>
            <div class="recap-row"><span>Finition / Version</span><span>${escapeHtml(v('v_finish'))} / ${escapeHtml(v('v_version'))}</span></div>
            <div class="recap-row"><span>Etat</span><span>${escapeHtml(v('v_condition'))}</span></div>
            <div class="recap-row"><span>Couleur</span><span>${escapeHtml(v('v_color'))}</span></div>
            <div class="recap-row"><span>Permis</span><span>${escapeHtml(v('v_license'))}</span></div>
        </div>
        <div class="recap-card"><div class="recap-card-title">Photos (${files.length})</div>
            <div class="recap-photos">${files.slice(0, 6).map(f => `<img src="${URL.createObjectURL(f)}" class="recap-photo" alt="">`).join('')}${files.length > 6 ? `<div style="width:50px;height:50px;border-radius:8px;background:#eee;display:flex;align-items:center;justify-content:center;font-weight:700;">+${files.length-6}</div>` : ''}</div>
        </div>
        <div class="recap-card" style="grid-column:span 2"><div class="recap-card-title">Equipements (${feats.length})</div>
            <div class="recap-features">${feats.length ? feats.map(f => `<span class="recap-tag">${escapeHtml(f)}</span>`).join('') : '<span style="color:#aaa">Aucun equipement selectionne</span>'}</div>
        </div>
    `;
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// Modal brouillon
const draftModal = document.getElementById('draftModal');
function openDraftModal() { if (draftModal) draftModal.classList.add('open'); }
function closeDraftModal() { if (draftModal) draftModal.classList.remove('open'); }

// Sauvegarde brouillon
document.getElementById('saveDraftBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    openDraftModal();
});

document.getElementById('confirmDraftBtn')?.addEventListener('click', async () => {
    closeDraftModal();

    const formData = new FormData(document.getElementById('adForm'));
    formData.append('_token', '{{ csrf_token() }}');

    try {
        const response = await fetch('{{ route("ads.draft") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showToast(data.message || 'Brouillon enregistre avec succes', 'success');
        } else {
            showToast(data.message || 'Erreur lors de l\'enregistrement', 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showToast('Erreur de connexion. Veuillez reessayer.', 'error');
    }
});

// Auto-hide des messages d'erreur de session
setTimeout(() => {
    const sessionError = document.getElementById('sessionError');
    const validationErrors = document.getElementById('validationErrors');
    if (sessionError) setTimeout(() => sessionError.remove(), 5000);
    if (validationErrors) setTimeout(() => validationErrors.remove(), 5000);
}, 1000);

// Initialisation
buildNav();

// Restauration des donnees du brouillon
@if(isset($draftData) && is_array($draftData))
    setTimeout(() => {
        const draft = @json($draftData);
        if (draft.seller) {
            if (draft.seller.first_name) document.getElementById('s_fname').value = draft.seller.first_name;
            if (draft.seller.last_name) document.getElementById('s_lname').value = draft.seller.last_name;
            if (draft.seller.email) document.getElementById('s_email').value = draft.seller.email;
            if (draft.seller.phone) document.getElementById('s_phone').value = draft.seller.phone;
            if (draft.seller.city) document.getElementById('s_city').value = draft.seller.city;
        }
        if (draft.bank) {
            if (draft.bank.account_holder_name) document.getElementById('b_holder').value = draft.bank.account_holder_name;
            if (draft.bank.iban) document.getElementById('b_iban').value = draft.bank.iban;
            if (draft.bank.bic) document.getElementById('b_bic').value = draft.bank.bic;
            const bankNameInput = document.querySelector('input[name="bank[bank_name]"]');
            if (bankNameInput && draft.bank.bank_name) bankNameInput.value = draft.bank.bank_name;
        }
        if (draft.ad) {
            if (draft.ad.title) document.getElementById('a_title').value = draft.ad.title;
            if (draft.ad.price) document.getElementById('a_price').value = draft.ad.price;
            if (draft.ad.city) document.getElementById('a_city').value = draft.ad.city;
            const descTextarea = document.querySelector('textarea[name="ad[description]"]');
            if (descTextarea && draft.ad.description) descTextarea.value = draft.ad.description;
        }
        if (draft.vehicle) {
            if (draft.vehicle.brand) document.getElementById('v_brand').value = draft.vehicle.brand;
            if (draft.vehicle.model) document.getElementById('v_model').value = draft.vehicle.model;
            if (draft.vehicle.year) document.getElementById('v_year').value = draft.vehicle.year;
            if (draft.vehicle.mileage) document.getElementById('v_mileage').value = draft.vehicle.mileage;
            if (draft.vehicle.fuel_type) {
                const fuelRadio = document.querySelector(`input[name="vehicle[fuel_type]"][value="${draft.vehicle.fuel_type}"]`);
                if (fuelRadio) fuelRadio.checked = true;
            }
            if (draft.vehicle.gearbox) {
                const gearboxRadio = document.querySelector(`input[name="vehicle[gearbox]"][value="${draft.vehicle.gearbox}"]`);
                if (gearboxRadio) gearboxRadio.checked = true;
            }
            const fields = ['color','din_power','fiscal_power','doors','seats','upholstery','finish','version','history','license'];
            fields.forEach(f => {
                if (draft.vehicle[f]) {
                    const el = document.querySelector(`[name="vehicle[${f}]"]`);
                    if (el) el.value = draft.vehicle[f];
                }
            });
            if (draft.vehicle.body_type) {
                const bodySelect = document.querySelector('select[name="vehicle[body_type]"]');
                if (bodySelect) bodySelect.value = draft.vehicle.body_type;
            }
            if (draft.vehicle.critair !== undefined && draft.vehicle.critair !== null) {
                const critairSelect = document.querySelector('select[name="vehicle[critair]"]');
                if (critairSelect) critairSelect.value = draft.vehicle.critair;
            }
            if (draft.vehicle.condition) {
                const conditionSelect = document.querySelector('select[name="vehicle[condition]"]');
                if (conditionSelect) conditionSelect.value = draft.vehicle.condition;
            }
        }
        if (draft.features && Array.isArray(draft.features)) {
            draft.features.forEach(feature => {
                const checkbox = Array.from(document.querySelectorAll('input[name="features[]"]')).find(cb => cb.value === feature);
                if (checkbox) checkbox.checked = true;
            });
        }
        if (draft.photos && draft.photos.length > 0) {
            showToast('Brouillon charge avec ' + draft.photos.length + ' photos', 'info');
        } else {
            showToast('Brouillon charge avec succes', 'success');
        }
    }, 300);
@endif
</script>
</body>
</html>
