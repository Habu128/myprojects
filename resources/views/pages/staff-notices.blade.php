<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Notices | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL STAFF</div>
        <h1>Public notices</h1>
        <p class="content-page-lead">Published notices appear on the news page. Draft notices are visible here only.</p>
        @if (session('notice_created') || session('notice_updated') || session('notice_deleted'))
            <div class="feedback-success">The notices list was updated.</div>
        @endif
        <div class="content-page-actions">
            <a class="button button-primary" href="{{ route('staff.notices.create') }}">Add a notice <span aria-hidden="true">→</span></a>
            <a class="text-link" href="{{ route('updates') }}">View public page <span aria-hidden="true">↗</span></a>
        </div>
        <div class="content-page-grid">
            @forelse ($notices as $notice)
                <article class="content-page-card">
                    <span class="card-kicker">{{ $notice->is_published ? 'PUBLISHED' : 'DRAFT' }}</span>
                    <h2>{{ $notice->title }}</h2>
                    <p>{{ \Illuminate\Support\Str::limit($notice->body, 180) }}</p>
                    <p class="directory-status">{{ $notice->publishedDate()->format('j M Y') }}</p>
                    <div class="staff-page-actions">
                        <a class="text-link" href="{{ route('staff.notices.edit', $notice) }}">Edit <span aria-hidden="true">↗</span></a>
                        <form method="POST" action="{{ route('staff.notices.destroy', $notice) }}" onsubmit="return confirm('Delete this notice?');">
                            @csrf
                            @method('DELETE')
                            <button class="button button-secondary" type="submit">Delete</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="content-page-empty"><span class="content-page-icon">✳</span><div><h2>No notices yet</h2><p>Add the first notice when there is verified information to publish.</p></div></div>
            @endforelse
        </div>
        {{ $notices->links() }}
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
