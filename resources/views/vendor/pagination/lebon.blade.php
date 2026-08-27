@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="lebon-pagination">
        @foreach ($paginator->linkCollection() as $link)
            @if ($link['url'] === null)
                <span class="lebon-page-link disabled">{!! $link['label'] !!}</span>
            @elseif ($link['active'])
                <span class="lebon-page-link active" aria-current="page">{!! $link['label'] !!}</span>
            @else
                <a href="{{ $link['url'] }}" class="lebon-page-link">{!! $link['label'] !!}</a>
            @endif
        @endforeach
    </nav>

    <style>
        .lebon-pagination {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
        }
        .lebon-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            background: var(--card);
            text-decoration: none;
            transition: border-color .15s, background .15s, color .15s;
        }
        a.lebon-page-link:hover {
            border-color: var(--orange);
            color: var(--orange);
        }
        .lebon-page-link.active {
            background: var(--orange);
            border-color: var(--orange);
            color: #fff;
        }
        .lebon-page-link.disabled {
            color: var(--muted);
            cursor: default;
            opacity: .5;
        }
    </style>
@endif
