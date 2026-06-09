<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>Mes favoris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet"/>
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        :root{
            --orange:#ff6e14;
            --navy:#001f5c;
            --bg:#f3f2ef;
            --surface:#fff;
            --border:#e8e8e8;
            --text:#1a1a1a;
            --muted:#696969;
            --green:#00a866;
            --r8:8px;--r12:12px;--r30:30px;
        }
        body{font-family:'Nunito Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;max-width:430px;margin:0 auto;}
        a{color:inherit;text-decoration:none;}
        img{display:block;max-width:100%;}

        /* Header */
        .fav-header{
            position:sticky;top:0;z-index:50;
            background:var(--surface);
            border-bottom:1px solid var(--border);
            display:flex;align-items:center;gap:12px;
            padding:14px 16px;
        }
        .fav-header-back{
            width:38px;height:38px;border-radius:50%;
            background:var(--bg);border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            flex-shrink:0;
        }
        .fav-header-back svg{width:18px;height:18px;stroke:var(--text);stroke-width:2.5;fill:none;}
        .fav-header-title{font-size:17px;font-weight:900;}
        .fav-header-count{font-size:13px;color:var(--muted);font-weight:600;margin-left:auto;}

        /* Empty state */
        .empty-state{
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            gap:16px;padding:60px 32px;text-align:center;
        }
        .empty-icon{
            width:72px;height:72px;border-radius:50%;
            background:#fdecea;
            display:flex;align-items:center;justify-content:center;
        }
        .empty-icon svg{width:32px;height:32px;stroke:#e0392d;stroke-width:1.8;fill:none;}
        .empty-title{font-size:18px;font-weight:900;}
        .empty-sub{font-size:14px;color:var(--muted);line-height:1.6;}

        /* Ad card */
        .ads-list{display:flex;flex-direction:column;gap:0;}
        .ad-card{
            background:var(--surface);
            border-bottom:1px solid var(--border);
            display:flex;gap:12px;
            padding:14px 16px;
            position:relative;
        }
        .ad-card:active{background:#f8f8f8;}
        .ad-thumb{
            width:90px;height:70px;border-radius:var(--r8);
            object-fit:cover;flex-shrink:0;
            background:var(--bg);
        }
        .ad-thumb-placeholder{
            width:90px;height:70px;border-radius:var(--r8);
            background:var(--bg);flex-shrink:0;
            display:flex;align-items:center;justify-content:center;
        }
        .ad-thumb-placeholder svg{width:22px;height:22px;stroke:var(--muted);stroke-width:1.5;fill:none;}
        .ad-info{flex:1;min-width:0;}
        .ad-title{
            font-size:14px;font-weight:800;
            white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
            margin-bottom:4px;
        }
        .ad-meta{font-size:12px;color:var(--muted);margin-bottom:6px;}
        .ad-price{font-size:17px;font-weight:900;color:var(--text);}
        .ad-city{font-size:11px;color:var(--muted);}
        .ad-unlike{
            position:absolute;top:14px;right:16px;
            width:34px;height:34px;border-radius:50%;
            background:var(--bg);border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
        }
        .ad-unlike svg{width:16px;height:16px;stroke:#e0392d;fill:#e0392d;stroke-width:1.5;}

        /* Bottom spacer */
        .bottom-spacer{height:32px;}
    </style>
</head>
<body>

<div class="fav-header">
    <button class="fav-header-back" onclick="history.back()">
        <svg viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    </button>
    <span class="fav-header-title">Mes favoris</span>
    <span class="fav-header-count">{{ $ads->count() }} annonce{{ $ads->count() !== 1 ? 's' : '' }}</span>
</div>

@if($ads->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        </div>
        <div class="empty-title">Aucun favori pour l'instant</div>
        <p class="empty-sub">Appuyez sur le cœur ❤️ d'une annonce pour l'ajouter à vos favoris et la retrouver ici.</p>
    </div>
@else
    <div class="ads-list" id="adsList">
        @foreach($ads as $ad)
        <div class="ad-card" id="card-{{ $ad->id }}">
            <a href="{{ route('ads.public', ['c' => $ad->share_token]) }}" style="display:contents;">
                @if($ad->photos->isNotEmpty())
                    <img class="ad-thumb" src="{{ $ad->photos->first()->url }}" alt="{{ $ad->title }}"/>
                @else
                    <div class="ad-thumb-placeholder">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    </div>
                @endif
                <div class="ad-info">
                    <div class="ad-title">{{ $ad->title }}</div>
                    <div class="ad-meta">
                        @if($ad->vehicle){{ $ad->vehicle->year }} · {{ number_format($ad->vehicle->mileage,0,',',' ') }} km · {{ ucfirst($ad->vehicle->fuel_type ?? '') }}@endif
                    </div>
                    <div class="ad-price">{{ $ad->formatted_price }}</div>
                    <div class="ad-city">
                        {{ $ad->city }}@if($ad->postal_code) ({{ $ad->postal_code }})@endif
                    </div>
                </div>
            </a>
            <button class="ad-unlike" onclick="unlike({{ $ad->id }}, this)" title="Retirer des favoris">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </button>
        </div>
        @endforeach
    </div>
@endif

<div class="bottom-spacer"></div>

<script>
const csrf = '{{ csrf_token() }}';

async function unlike(adId, btn) {
    btn.disabled = true;
    try {
        const resp = await fetch(`/annonces/${adId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await resp.json();
        if (!data.liked) {
            const card = document.getElementById('card-' + adId);
            card.style.transition = 'opacity .3s';
            card.style.opacity = '0';
            setTimeout(() => card.remove(), 300);

            // Update count
            const countEl = document.querySelector('.fav-header-count');
            const remaining = document.querySelectorAll('.ad-card').length - 1;
            countEl.textContent = remaining + ' annonce' + (remaining !== 1 ? 's' : '');

            if (remaining === 0) {
                document.getElementById('adsList').innerHTML = '';
                document.querySelector('.ads-list')?.remove();
                document.querySelector('.bottom-spacer').insertAdjacentHTML('beforebegin', `
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" stroke="#e0392d" fill="none" stroke-width="1.8"/></svg>
                        </div>
                        <div class="empty-title">Aucun favori pour l'instant</div>
                        <p class="empty-sub">Appuyez sur le cœur ❤️ d'une annonce pour l'ajouter à vos favoris.</p>
                    </div>
                `);
            }
        }
    } catch (e) {
        console.error(e);
        btn.disabled = false;
    }
}
</script>

</body>
</html>
