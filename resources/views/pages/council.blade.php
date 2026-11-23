<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Publicly sourced leadership and council directory information for Shani Local Government.">
    <meta name="theme-color" content="#103d28">
    <title>Council directory | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> {{ $page['eyebrow'] }}</div>
        <h1>{{ $page['title'] }}</h1>
        <p class="content-page-lead">{{ $page['description'] }}</p>
        <div class="transparency-status"><span class="updates-icon" aria-hidden="true">⌂</span><div><h2>Verified council directory not available</h2><p>{{ $page['details'] }}</p></div></div>
        @if ($chairmanPhotos !== [])
            <section class="content-page-card content-page-card--chairman">
                <div class="chairman-gallery">
                    @foreach ($chairmanPhotos as $photo)
                        <img class="chairman-photo" src="{{ $photo['url'] }}" alt="{{ $photo['alt'] }}" loading="lazy">
                    @endforeach
                </div>
                <p class="portrait-caption">Council photographs uploaded by staff. Confirm identities against current council records.</p>
            </section>
        @endif
        <div class="content-page-grid transparency-grid">
            @foreach ($page['items'] as $item)
                <article class="content-page-card @if (!empty($item['photos'])) content-page-card--chairman @endif">
                    @php
                        $photos = $chairmanPhotos === []
                            ? collect($item['photos'] ?? [])->map(fn (string $photo): array => [
                                'url' => asset($photo),
                                'alt' => 'Supplied photograph '.$loop->iteration.' for '.$item['title'],
                            ])->all()
                            : [];
                    @endphp
                    @if ($photos !== [])
                        <div class="chairman-gallery">
                            @foreach ($photos as $photo)
                                <img class="chairman-photo" src="{{ $photo['url'] }}" alt="{{ $photo['alt'] }}" loading="lazy">
                            @endforeach
                        </div>
                        <p class="portrait-caption">{{ $item['photo_caption'] ?? '' }}</p>
                    @else
                        <span class="content-page-icon">▤</span>
                    @endif
                    @if (!empty($item['kicker']))<span class="card-kicker">{{ $item['kicker'] }}</span>@endif
                    <h2>{{ $item['title'] }}</h2>
                    <p>{{ $item['description'] }}</p>
                    @if (!empty($item['status']))<p class="directory-status">{{ $item['status'] }}</p>@endif
                    @if (!empty($item['route']))<a class="content-card-link" href="{{ route($item['route']) }}">{{ $item['link_text'] ?? 'Read more' }} <span aria-hidden="true">→</span></a>@endif
                </article>
            @endforeach
        </div>
        @if ($page['sources'] !== [])
            <section class="content-sources" aria-labelledby="council-sources-title">
                <h2 id="council-sources-title">Sources for the leadership entry</h2>
                <ul>
                    @foreach ($page['sources'] as $source)
                        <li><a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer">{{ $source['title'] }} <span aria-hidden="true">↗</span></a></li>
                    @endforeach
                </ul>
            </section>
        @endif
        <div class="content-page-actions"><a class="button button-primary" href="{{ route('feedback') }}">Send a directory correction <span aria-hidden="true">→</span></a><a class="text-link" href="{{ route('transparency') }}">View budgets & public records <span aria-hidden="true">↗</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
