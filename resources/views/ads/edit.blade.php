<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
    <title>Modifier — {{ $ad->title }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="/css/lebon.css"/>
    <style>
        .content { padding: 32px; }

        .edit-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .edit-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 24px;
            color: var(--text);
        }
        .edit-header-sub { font-size: 13px; color: var(--muted); margin-top: 4px; }

        /* Sections */
        .edit-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            margin-bottom: 20px;
        }
        .edit-section h2 {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .edit-section h2::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* Grid */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .col-2 { grid-column: span 2; }
        .col-3 { grid-column: span 3; }

        /* Fields */
        .field { display: flex; flex-direction: column; gap: 6px; }
        .field label { font-size: 13px; font-weight: 700; color: var(--text); }
        .field input, .field select, .field textarea {
            height: 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 0 12px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: var(--bg);
            outline: none;
            transition: border-color .2s;
        }
        .field textarea { height: auto; padding: 10px 12px; resize: vertical; }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: var(--orange);
            background: #fff;
        }
        .field .req { color: var(--orange); }
        .error-msg { font-size: 12px; color: var(--red); }

        .input-group { display: flex; }
        .input-group input { border-radius: 10px 0 0 10px; flex: 1; }
        .input-group-suffix {
            height: 42px; padding: 0 14px;
            background: var(--border);
            border: 1.5px solid var(--border);
            border-left: none;
            border-radius: 0 10px 10px 0;
            display: flex; align-items: center;
            font-size: 13px; font-weight: 700; color: var(--muted);
        }

        /* Radio cards */
        .radio-cards { display: flex; gap: 8px; flex-wrap: wrap; }
        .radio-card { position: relative; }
        .radio-card input { position: absolute; opacity: 0; width: 0; height: 0; }
        .radio-card label {
            display: flex; align-items: center; gap: 6px;
            height: 38px; padding: 0 16px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px; font-weight: 600;
            cursor: pointer; color: var(--text);
            transition: all .15s;
        }
        .radio-card input:checked + label {
            border-color: var(--orange);
            background: var(--orange-lt);
            color: var(--orange);
        }

        /* Équipements */
        .equip-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
        .equip-check { position: relative; }
        .equip-check input { position: absolute; opacity: 0; width: 0; height: 0; }
        .equip-check label {
            display: flex; align-items: center; gap: 8px;
            padding: 9px 12px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 12px; font-weight: 600;
            cursor: pointer; color: var(--text);
            transition: all .15s;
        }
        .equip-check label::before {
            content: '';
            width: 16px; height: 16px; flex-shrink: 0;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            background: #fff;
            transition: all .15s;
        }
        .equip-check input:checked + label {
            border-color: var(--orange);
            background: var(--orange-lt);
            color: var(--orange);
        }
        .equip-check input:checked + label::before {
            background: var(--orange);
            border-color: var(--orange);
        }

        /* Photos */
        .photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }
        .photo-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 4/3;
            background: var(--bg);
            border: 1.5px solid var(--border);
            cursor: grab;
            transition: opacity .2s, transform .15s, border-color .15s;
        }
        .photo-item.dragging { opacity: .3; cursor: grabbing; }
        .photo-item.drag-over { border-color: var(--orange) !important; transform: scale(1.04); outline: 2px solid var(--orange); }
        .photo-item img { width: 100%; height: 100%; object-fit: cover; display: block; pointer-events: none; }
        .photo-del {
            position: absolute; top: 6px; right: 6px;
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(0,0,0,.6);
            border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #fff; opacity: 0; transition: opacity .15s;
        }
        .photo-item:hover .photo-del { opacity: 1; }
        .photo-del svg { width: 13px; height: 13px; stroke: #fff; stroke-width: 2.5; fill: none; }
        .photo-pin {
            position: absolute; top: 6px; left: 6px;
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(14,30,61,.75);
            border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #fff; opacity: 0; transition: opacity .15s;
            z-index: 2;
        }
        .photo-item:hover .photo-pin { opacity: 1; }
        .photo-main-badge {
            position: absolute; bottom: 5px; left: 5px;
            background: var(--orange); color: #fff;
            font-size: 9px; font-weight: 800;
            padding: 2px 6px; border-radius: 4px;
        }

        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            transition: all .2s;
        }
        .upload-zone:hover { border-color: var(--orange); background: var(--orange-lt); }
        .upload-zone input { display: none; }
        .upload-zone-text { font-size: 13px; color: var(--muted); margin-top: 8px; }

        /* Actions bas */
        .edit-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 0;
        }

        @media (max-width: 768px) {
            .content { padding: 20px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .col-2, .col-3 { grid-column: span 1; }
            .equip-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="app">

    @include('partials.sidebar')

    <div class="main">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="breadcrumb">
                <a href="{{ route('ads.index') }}">Mes annonces</a>
                <span>›</span>
                <a href="{{ route('ads.show', $ad) }}" style="max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ad->title }}</a>
                <span>›</span>
                <strong>Modifier</strong>
            </div>
            <div class="user-menu">
                <div class="user-avatar" onclick="openLogoutModal()">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content">

            @if($errors->any())
                <div class="flash error" style="margin-bottom:20px;">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Veuillez corriger les erreurs ci-dessous.
                </div>
            @endif

            <form method="POST" action="{{ route('ads.update', $ad) }}" enctype="multipart/form-data" id="editForm">
                @csrf
                @method('PUT')

                <div class="edit-header">
                    <div>
                        <h1>Modifier l'annonce</h1>
                        <div class="edit-header-sub">{{ $ad->title }} · Mise à jour le {{ $ad->updated_at->format('d/m/Y') }}</div>
                    </div>
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Enregistrer
                    </button>
                </div>

                {{-- ── Informations de l'annonce ── --}}
                <div class="edit-section">
                    <h2>Informations de l'annonce</h2>
                    <div class="grid-2">
                        <div class="field col-2">
                            <label>Titre <span class="req">*</span></label>
                            <input type="text" name="ad[title]" value="{{ old('ad.title', $ad->title) }}" placeholder="Ex : Audi A5 3.0 TDI 245ch">
                            @error('ad.title')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Prix <span class="req">*</span></label>
                            <div class="input-group">
                                <input type="number" name="ad[price]" value="{{ old('ad.price', (float)$ad->price) }}" min="0" step="1">
                                <span class="input-group-suffix">€</span>
                            </div>
                            @error('ad.price')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Statut</label>
                            <select name="ad[status]">
                                <option value="active"  {{ old('ad.status', $ad->status) === 'active'  ? 'selected' : '' }}>Active</option>
                                <option value="paused"  {{ old('ad.status', $ad->status) === 'paused'  ? 'selected' : '' }}>En pause</option>
                                <option value="sold"    {{ old('ad.status', $ad->status) === 'sold'    ? 'selected' : '' }}>Vendue</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Ville <span class="req">*</span></label>
                            <input type="text" name="ad[city]" value="{{ old('ad.city', $ad->city) }}" placeholder="Paris">
                            @error('ad.city')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Code postal</label>
                            <input type="text" name="ad[postal_code]" value="{{ old('ad.postal_code', $ad->postal_code) }}" placeholder="75001" maxlength="10">
                        </div>
                        <div class="field">
                            <label>Nombre de favoris</label>
                            <input type="number" name="ad[likes_count]" value="{{ old('ad.likes_count', $ad->likes_count) }}" min="0" placeholder="0">
                        </div>
                        <div class="field col-2">
                            <label>Description</label>
                            <textarea name="ad[description]" rows="5" placeholder="Décrivez l'état général, l'historique...">{{ old('ad.description', $ad->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── Véhicule ── --}}
                @if($ad->vehicle)
                <div class="edit-section">
                    <h2>Détails du véhicule</h2>
                    <div class="grid-3">
                        <div class="field">
                            <label>Marque <span class="req">*</span></label>
                            <input type="text" name="vehicle[brand]" value="{{ old('vehicle.brand', $ad->vehicle->brand) }}" placeholder="Audi">
                            @error('vehicle.brand')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Modèle <span class="req">*</span></label>
                            <input type="text" name="vehicle[model]" value="{{ old('vehicle.model', $ad->vehicle->model) }}" placeholder="A5">
                            @error('vehicle.model')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Année <span class="req">*</span></label>
                            <input type="number" name="vehicle[year]" value="{{ old('vehicle.year', $ad->vehicle->year) }}" min="1900" max="{{ date('Y') }}" placeholder="2020">
                            @error('vehicle.year')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>Kilométrage <span class="req">*</span></label>
                            <div class="input-group">
                                <input type="number" name="vehicle[mileage]" value="{{ old('vehicle.mileage', $ad->vehicle->mileage) }}" min="0" placeholder="120000">
                                <span class="input-group-suffix">km</span>
                            </div>
                            @error('vehicle.mileage')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="field">
                            <label>1ère mise en circulation</label>
                            <input type="date" name="vehicle[first_registration_date]" value="{{ old('vehicle.first_registration_date', $ad->vehicle->first_registration_date) }}">
                        </div>
                        <div class="field">
                            <label>Puissance DIN</label>
                            <div class="input-group">
                                <input type="number" name="vehicle[din_power]" value="{{ old('vehicle.din_power', $ad->vehicle->din_power) }}" min="0" placeholder="245">
                                <span class="input-group-suffix">ch</span>
                            </div>
                        </div>
                        <div class="field">
                            <label>Puissance fiscale</label>
                            <div class="input-group">
                                <input type="number" name="vehicle[fiscal_power]" value="{{ old('vehicle.fiscal_power', $ad->vehicle->fiscal_power) }}" min="0" placeholder="14">
                                <span class="input-group-suffix">cv</span>
                            </div>
                        </div>
                        <div class="field">
                            <label>Portes</label>
                            <input type="number" name="vehicle[doors]" value="{{ old('vehicle.doors', $ad->vehicle->doors) }}" min="1" max="9" placeholder="5">
                        </div>
                        <div class="field">
                            <label>Places</label>
                            <input type="number" name="vehicle[seats]" value="{{ old('vehicle.seats', $ad->vehicle->seats) }}" min="1" max="20" placeholder="5">
                        </div>
                    </div>

                    <div style="margin-top:16px;" class="field">
                        <label>Carburant <span class="req">*</span></label>
                        <div class="radio-cards">
                            @foreach(['diesel'=>'Diesel','essence'=>'Essence','hybride'=>'Hybride','electrique'=>'Électrique','gpl'=>'GPL'] as $val => $label)
                            <div class="radio-card">
                                <input type="radio" name="vehicle[fuel_type]" id="fuel_{{ $val }}" value="{{ $val }}"
                                    {{ old('vehicle.fuel_type', $ad->vehicle->fuel_type) === $val ? 'checked' : '' }}>
                                <label for="fuel_{{ $val }}">{{ $label }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('vehicle.fuel_type')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>

                    <div style="margin-top:16px;" class="field">
                        <label>Boîte de vitesse <span class="req">*</span></label>
                        <div class="radio-cards">
                            <div class="radio-card">
                                <input type="radio" name="vehicle[gearbox]" id="gb_man" value="manuelle"
                                    {{ old('vehicle.gearbox', $ad->vehicle->gearbox) === 'manuelle' ? 'checked' : '' }}>
                                <label for="gb_man">Manuelle</label>
                            </div>
                            <div class="radio-card">
                                <input type="radio" name="vehicle[gearbox]" id="gb_auto" value="automatique"
                                    {{ old('vehicle.gearbox', $ad->vehicle->gearbox) === 'automatique' ? 'checked' : '' }}>
                                <label for="gb_auto">Automatique</label>
                            </div>
                        </div>
                        @error('vehicle.gearbox')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid-3" style="margin-top:16px;">
                        <div class="field">
                            <label>Carrosserie</label>
                            <select name="vehicle[body_type]">
                                <option value="">—</option>
                                @foreach(['Berline','SUV','Coupe','Cabriolet','Break','Citadine','Monospace','Utilitaire'] as $bt)
                                <option value="{{ $bt }}" {{ old('vehicle.body_type', $ad->vehicle->body_type) === $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Couleur</label>
                            <input type="text" name="vehicle[color]" value="{{ old('vehicle.color', $ad->vehicle->color) }}" placeholder="Noir">
                        </div>
                        <div class="field">
                            <label>Sellerie</label>
                            <input type="text" name="vehicle[upholstery]" value="{{ old('vehicle.upholstery', $ad->vehicle->upholstery) }}" placeholder="Cuir">
                        </div>
                        <div class="field">
                            <label>Crit'Air</label>
                            <select name="vehicle[critair]">
                                <option value="">—</option>
                                @foreach([0=>'0 (Électrique)',1=>'1',2=>'2',3=>'3',4=>'4',5=>'5'] as $v => $lbl)
                                <option value="{{ $v }}" {{ (string)old('vehicle.critair', $ad->vehicle->critair) === (string)$v ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>État</label>
                            <select name="vehicle[condition]">
                                <option value="">—</option>
                                @foreach(['Excellent','Très bon','Bon','Correct','À réparer'] as $c)
                                <option value="{{ $c }}" {{ old('vehicle.condition', $ad->vehicle->condition) === $c ? 'selected' : '' }}>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Équipements ── --}}
                <div class="edit-section">
                    <h2>Équipements</h2>
                    @php
                        $allFeatures = [
                            'Toit ouvrant','Caméra de recul','Régulateur de vitesse',
                            'Limiteur de vitesse','Sièges chauffants','Climatisation auto',
                            'GPS','Bluetooth','Jantes alliage','Xénons / LED',
                            'Démarrage sans clé','Vitres électriques','Rétros électriques',
                            'Attelage','Aide au stationnement','Volant chauffant',
                        ];
                        $currentFeatures = old('features', $ad->features->pluck('label')->toArray());
                    @endphp
                    <div class="equip-grid">
                        @foreach($allFeatures as $feat)
                        <div class="equip-check">
                            <input type="checkbox" name="features[]" id="feat_{{ Str::slug($feat) }}"
                                   value="{{ $feat }}" {{ in_array($feat, $currentFeatures) ? 'checked' : '' }}>
                            <label for="feat_{{ Str::slug($feat) }}">{{ $feat }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Photos ── --}}
                <div class="edit-section">
                    <h2>Photos ({{ $ad->photos->count() }} / 12)</h2>

                    @if($ad->photos->isNotEmpty())
                    <div class="photos-grid" id="photosGrid">
                        @foreach($ad->photos as $i => $photo)
                        <div class="photo-item" id="photo-{{ $photo->id }}" draggable="true">
                            <img src="{{ $photo->url }}" alt="Photo {{ $i+1 }}"/>
                            @if($i === 0)
                                <span class="photo-main-badge" id="badge-{{ $photo->id }}">Principal</span>
                            @endif
                            <button type="button" class="photo-del"
                                    onclick="deletePhoto({{ $photo->id }}, this)"
                                    title="Supprimer">
                                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            @if($i !== 0)
                            <button type="button" class="photo-pin"
                                    onclick="movePhotoToFirst({{ $photo->id }})"
                                    title="Mettre en 1ère position">
                                <svg width="11" height="11" fill="white" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/></svg>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($ad->photos->count() < 12)
                    <label class="upload-zone" for="photoInput">
                        <svg width="32" height="32" fill="none" stroke="var(--muted)" stroke-width="1.5" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <div class="upload-zone-text">Cliquer pour ajouter des photos<br><small>JPG, PNG, WEBP · max 5 Mo · {{ 12 - $ad->photos->count() }} slot(s) restant(s)</small></div>
                        <input type="file" id="photoInput" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" onchange="previewPhotos(this)">
                    </label>
                    <div id="newPreview" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px;"></div>
                    @endif
                </div>

                {{-- Actions bas de page --}}
                <div class="edit-actions">
                    <a href="{{ route('ads.show', $ad) }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>

            </form>
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

<script src="/js/lebon.js"></script>
<script>
const csrf = '{{ csrf_token() }}';

async function deletePhoto(photoId, btn) {
    if (!confirm('Supprimer cette photo ?')) return;
    btn.disabled = true;
    try {
        const resp = await fetch('{{ route("ads.photos.destroy", [$ad, ":photo"]) }}'.replace(':photo', photoId), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await resp.json();
        if (data.success) {
            const el = document.getElementById('photo-' + photoId);
            el.style.transition = 'opacity .25s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 250);

            // Mise à jour du compteur dans le titre de section
            const h2 = document.querySelector('.edit-section h2');
            if (h2) h2.textContent = 'Photos (' + data.remaining + ' / 12)';
        }
    } catch (e) {
        console.error(e);
        btn.disabled = false;
    }
}

function previewPhotos(input) {
    const container = document.getElementById('newPreview');
    container.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.style.cssText = 'width:90px;height:70px;border-radius:8px;overflow:hidden;border:1.5px solid var(--border);';
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;display:block;';
            div.appendChild(img);
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ── Drag-and-drop réorganisation photos ──────────────────────────
let editDragSrc = null;
const reorderUrl = '{{ route("ads.photos.reorder", $ad) }}';

function initPhotoDrag() {
    const grid = document.getElementById('photosGrid');
    if (!grid) return;
    grid.querySelectorAll('.photo-item').forEach(item => {
        item.addEventListener('dragstart', e => {
            editDragSrc = item;
            e.dataTransfer.effectAllowed = 'move';
            setTimeout(() => item.classList.add('dragging'), 0);
        });
        item.addEventListener('dragend', () => {
            item.classList.remove('dragging');
            grid.querySelectorAll('.photo-item').forEach(el => el.classList.remove('drag-over'));
            editDragSrc = null;
        });
        item.addEventListener('dragover', e => {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (editDragSrc && editDragSrc !== item) {
                grid.querySelectorAll('.photo-item').forEach(el => el.classList.remove('drag-over'));
                item.classList.add('drag-over');
            }
        });
        item.addEventListener('dragleave', () => item.classList.remove('drag-over'));
        item.addEventListener('drop', e => {
            e.preventDefault();
            item.classList.remove('drag-over');
            if (!editDragSrc || editDragSrc === item) return;
            // Réinsertion dans le DOM
            const items = [...grid.querySelectorAll('.photo-item')];
            const fromIdx = items.indexOf(editDragSrc);
            const toIdx   = items.indexOf(item);
            if (fromIdx < toIdx) {
                grid.insertBefore(editDragSrc, item.nextSibling);
            } else {
                grid.insertBefore(editDragSrc, item);
            }
            refreshPrimaryBadges();
            savePhotoOrder();
        });
    });
}

function movePhotoToFirst(photoId) {
    const grid = document.getElementById('photosGrid');
    const el   = document.getElementById('photo-' + photoId);
    if (!grid || !el) return;
    grid.insertBefore(el, grid.firstChild);
    refreshPrimaryBadges();
    savePhotoOrder();
}

function refreshPrimaryBadges() {
    const grid = document.getElementById('photosGrid');
    if (!grid) return;
    grid.querySelectorAll('.photo-item').forEach((item, idx) => {
        // Badge "Principal"
        let badge = item.querySelector('.photo-main-badge');
        if (idx === 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'photo-main-badge';
                badge.textContent = 'Principal';
                item.appendChild(badge);
            }
        } else {
            if (badge) badge.remove();
        }
        // Bouton pin — cacher sur le premier, montrer sur les autres
        const pin = item.querySelector('.photo-pin');
        if (pin) pin.style.display = idx === 0 ? 'none' : '';
    });
}

async function savePhotoOrder() {
    const grid = document.getElementById('photosGrid');
    if (!grid) return;
    const ids = [...grid.querySelectorAll('.photo-item')].map(el => el.id.replace('photo-', ''));
    try {
        await fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order: ids }),
        });
    } catch (e) { console.error(e); }
}

document.addEventListener('DOMContentLoaded', initPhotoDrag);
</script>
</body>
</html>
