<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $page['description'] }} | {{ $localGovernmentName }}">
    <meta name="theme-color" content="#103d28">
    <title>{{ $page['title'] }} | {{ $localGovernmentName }}</title>
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
        @if ($page['items'] !== [])
            <div class="content-page-grid">
                @foreach ($page['items'] as $item)
                    <article class="content-page-card">
                        <span class="content-page-icon">{{ $item['icon'] ?? '✳' }}</span>
                        <h2>{{ $item['title'] }}</h2>
                        <p>{{ $item['description'] }}</p>
                        @if (!empty($item['route']))
                            <a class="content-card-link" href="{{ route($item['route']) }}">{{ $item['link_text'] ?? 'Read more' }} <span aria-hidden="true">→</span></a>
                        @elseif (!empty($item['url']))
                            <a class="content-card-link" href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer">{{ $item['link_text'] ?? 'Open link' }} <span aria-hidden="true">↗</span></a>
                        @endif
                    </article>
                @endforeach
            </div>
        @elseif (isset($notices) && $notices->isNotEmpty())
            <div class="content-page-grid">
                @foreach ($notices as $notice)
                    <article class="content-page-card"><span class="card-kicker">{{ $notice->publishedDate()->format('j M Y') }}</span><h2>{{ $notice->title }}</h2><p>{{ \Illuminate\Support\Str::limit($notice->body, 240) }}</p></article>
                @endforeach
            </div>
        @else
            <div class="content-page-empty"><span class="content-page-icon">✳</span><div><h2>No public notices are currently listed</h2><p>{{ $page['details'] }}</p></div></div>
        @endif
        @if (($page['items'] !== [] || (isset($notices) && $notices->isNotEmpty())) && $page['details'] !== '')
            <p class="content-page-note">{{ $page['details'] }}</p>
        @endif
        @if (!empty($page['sources']))
            <section class="content-sources" aria-labelledby="history-sources-title">
                <h2 id="history-sources-title">Sources</h2>
                <ul>
                    @foreach ($page['sources'] as $source)
                        <li><a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer">{{ $source['title'] }} <span aria-hidden="true">↗</span></a></li>
                    @endforeach
                </ul>
            </section>
        @endif
        <div class="content-page-actions"><a class="button button-primary" href="{{ route('feedback') }}">Send feedback <span aria-hidden="true">→</span></a><a class="text-link" href="{{ route('transparency') }}">Budgets & projects <span aria-hidden="true">↗</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
