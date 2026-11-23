<x-admin-shell :local-government-name="$localGovernmentName" title="Dashboard" active="dashboard">
    <div class="admin-heading">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL ADMIN</div>
            <h1>Website dashboard</h1>
            <p>Manage the information shown across the public website from one place.</p>
        </div>
        <a class="button button-primary" href="{{ route('staff.content.index') }}">Edit site content <span aria-hidden="true">→</span></a>
    </div>

    @if (session('map_refreshed'))
        <div class="admin-alert">The ward map cache has been cleared and will refresh on the next map visit.</div>
    @endif

    <section class="admin-grid" aria-label="Website summary">
        <article class="admin-stat"><span>Published pages</span><strong>{{ $stats['published_pages'] }}</strong><small>Draft pages: {{ $stats['draft_pages'] }}</small></article>
        <article class="admin-stat"><span>Live notices</span><strong>{{ $stats['live_notices'] }}</strong><small>Public announcements</small></article>
        <article class="admin-stat"><span>Active projects</span><strong>{{ $stats['active_projects'] }}</strong><small>Published council work</small></article>
        <article class="admin-stat"><span>Open reports</span><strong>{{ $stats['open_reports'] }}</strong><small>New or in review</small></article>
        <article class="admin-stat"><span>Media assets</span><strong>{{ $stats['media_assets'] }}</strong><small>Logos, photos and documents</small></article>
        <article class="admin-stat"><span>Ward map cache</span><strong>{{ $mapCached ? 'Ready' : 'Empty' }}</strong><small>Refreshed from the backend</small></article>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head"><div><span class="card-kicker">START HERE</span><h2>Quick actions</h2></div></div>
        <div class="admin-action-grid">
            <a href="{{ route('staff.content.index') }}"><span>✎</span><strong>Edit page content</strong><small>About, services, records and contact text</small></a>
            <a href="{{ route('staff.notices.create') }}"><span>＋</span><strong>Add a notice</strong><small>Publish verified announcements</small></a>
            <a href="{{ route('admin.media.index') }}"><span>⌂</span><strong>Upload media</strong><small>Logos, chairman photos and images</small></a>
            <a href="{{ route('admin.settings.edit') }}"><span>⚙</span><strong>Site settings</strong><small>Homepage, contact details and site logo</small></a>
            <a href="{{ route('council.projects.create') }}"><span>⌖</span><strong>Add a project</strong><small>Ward, status, source and evidence</small></a>
            <a href="{{ route('council.reports.index') }}"><span>✉</span><strong>Review reports</strong><small>Resident enquiries and tracking codes</small></a>
        </div>
    </section>

    <div class="admin-two-col">
        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">LATEST CONTENT</span><h2>Recent notices</h2></div><a class="text-link" href="{{ route('staff.notices.index') }}">All notices <span aria-hidden="true">↗</span></a></div>
            @forelse ($recentNotices as $notice)
                <div class="admin-list-row">
                    <div><strong>{{ $notice->title }}</strong><small>{{ $notice->publishedDate()->format('j M Y') }} · {{ $notice->is_published ? 'Published' : 'Draft' }}</small></div>
                    <a class="text-link" href="{{ route('staff.notices.edit', $notice) }}">Edit <span aria-hidden="true">↗</span></a>
                </div>
            @empty
                <p class="admin-empty">No notices have been added yet.</p>
            @endforelse
        </section>

        <section class="admin-panel">
            <div class="admin-panel-head"><div><span class="card-kicker">RESIDENTS</span><h2>Recent reports</h2></div><a class="text-link" href="{{ route('council.reports.index') }}">All reports <span aria-hidden="true">↗</span></a></div>
            @forelse ($recentReports as $report)
                <div class="admin-list-row">
                    <div><strong>{{ $report->topic ?: 'Resident enquiry' }}</strong><small>{{ $report->ward ?: 'No ward' }} · {{ $report->status }}</small></div>
                    <span class="admin-pill">{{ $report->tracking_code }}</span>
                </div>
            @empty
                <p class="admin-empty">No resident reports have been received yet.</p>
            @endforelse
        </section>
    </div>

    <section class="admin-panel">
        <div class="admin-panel-head">
            <div><span class="card-kicker">MAP DATA</span><h2>Ward map source</h2></div>
            <form method="post" action="{{ route('admin.map.refresh') }}">
                @csrf
                <button class="button button-secondary" type="submit">Refresh map data</button>
            </form>
        </div>
        <p class="admin-note">Boundaries, schools and health facilities are fetched and cached by Laravel. Clearing the cache makes the next map visit request fresh data.</p>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-head"><div><span class="card-kicker">SITE IDENTITY</span><h2>Current public settings</h2></div><a class="text-link" href="{{ route('admin.settings.edit') }}">Edit settings <span aria-hidden="true">↗</span></a></div>
        <dl class="admin-settings-list">
            <div><dt>Homepage title</dt><dd>{{ $settings['hero_title_line_1'] ?? '' }} {{ $settings['hero_title_line_2'] ?? '' }}</dd></div>
            <div><dt>Contact phone</dt><dd>{{ ($settings['contact_phone'] ?? '') ?: 'Not published' }}</dd></div>
            <div><dt>Contact email</dt><dd>{{ ($settings['contact_email'] ?? '') ?: 'Not published' }}</dd></div>
            <div><dt>Site logo</dt><dd>{{ ($settings['logo_path'] ?? '') ?: 'Default logo' }}</dd></div>
        </dl>
    </section>
</x-admin-shell>
