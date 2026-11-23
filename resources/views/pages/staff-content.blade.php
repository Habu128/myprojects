<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Site content | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL STAFF</div>
        <h1>Site content</h1>
        <p class="content-page-lead">Edit the public pages stored in the database. Changes appear on the live pages immediately.</p>
        @if (session('content_updated'))
            <div class="feedback-success">The page was updated.</div>
        @endif
        <div class="content-page-grid">
            @foreach ($pages as $contentPage)
                <article class="content-page-card">
                    <span class="card-kicker">{{ $contentPage->slug }}</span>
                    <h2>{{ $contentPage->title }}</h2>
                    <p>{{ $contentPage->description }}</p>
                    <p class="directory-status">{{ $contentPage->is_published ? 'Published' : 'Draft' }} · {{ $contentPage->path }}</p>
                    <a class="content-card-link" href="{{ route('staff.content.edit', $contentPage) }}">Edit page <span aria-hidden="true">→</span></a>
                </article>
            @endforeach
        </div>
        <div class="content-page-actions">
            <a class="button button-primary" href="{{ route('staff.notices.index') }}">Manage notices <span aria-hidden="true">→</span></a>
            <a class="text-link" href="{{ route('council.projects.index') }}">Council projects <span aria-hidden="true">↗</span></a>
        </div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
