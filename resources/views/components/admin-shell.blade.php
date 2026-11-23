@props([
    'localGovernmentName' => 'Shani Local Government',
    'title' => 'Admin',
    'active' => '',
])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>{{ $title }} | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell">
    <div class="admin-topbar">
        <div class="page-wrap admin-topbar-inner">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                <span class="admin-brand-mark" aria-hidden="true">✳</span>
                <span>{{ $localGovernmentName }}<small>COUNCIL ADMIN PANEL</small></span>
            </a>
            <div class="admin-top-actions">
                <span class="admin-user"><i class="status-dot"></i>{{ auth()->user()?->name }}</span>
                <a class="admin-view-link" href="{{ route('home') }}" target="_blank" rel="noopener noreferrer">View site <span aria-hidden="true">↗</span></a>
                <form method="post" action="{{ route('council.reports.logout') }}">
                    @csrf
                    <button class="button button-secondary" type="submit">Sign out</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-layout">
        <aside class="admin-sidebar" aria-label="Admin navigation">
            <div class="eyebrow"><span class="eyebrow-dot"></span> WORKSPACE</div>
            <nav>
                <a href="{{ route('admin.dashboard') }}" @if ($active === 'dashboard') aria-current="page" @endif>Dashboard</a>
                <a href="{{ route('staff.content.index') }}" @if ($active === 'content') aria-current="page" @endif>Page content</a>
                <a href="{{ route('staff.notices.index') }}" @if ($active === 'notices') aria-current="page" @endif>Public notices</a>
                <a href="{{ route('council.projects.index') }}" @if ($active === 'projects') aria-current="page" @endif>Projects</a>
                <a href="{{ route('council.reports.index') }}" @if ($active === 'reports') aria-current="page" @endif>Resident reports</a>
                <a href="{{ route('admin.media.index') }}" @if ($active === 'media') aria-current="page" @endif>Media uploads</a>
                <a href="{{ route('admin.settings.edit') }}" @if ($active === 'settings') aria-current="page" @endif>Site settings</a>
            </nav>
            <p class="admin-sidebar-note">Publish only verified information. Drafts stay hidden from the public site.</p>
        </aside>

        <main class="admin-main">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
