<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Resident reports | {{ $localGovernmentName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="staff-reports-heading">
            <div><div class="eyebrow"><span class="eyebrow-dot"></span> STAFF WORKSPACE</div><h1>Resident reports</h1><p class="content-page-lead">Review new reports and update the status residents see with their private reference code.</p></div>
            <div class="staff-page-actions"><a class="button button-primary" href="{{ route('council.projects.index') }}">Manage public projects</a><form method="post" action="{{ route('council.reports.logout') }}">@csrf<button class="button button-secondary" type="submit">Sign out</button></form></div>
        </div>
        @if (session('report_updated'))<div class="feedback-success" role="status"><strong>Status updated.</strong><span>The resident can now see the new status using their reference code.</span></div>@endif
        <div class="staff-report-list">
            @forelse ($enquiries as $enquiry)
                <article class="staff-report-card">
                    <div class="staff-report-meta"><strong>{{ $enquiry->topic ? str($enquiry->topic)->replace('-', ' ')->title() : 'Resident enquiry' }}</strong><span>{{ $enquiry->created_at->format('j M Y, g:i a') }}</span></div>
                    <p>{{ $enquiry->message }}</p>
                    <dl><div><dt>Reference</dt><dd>{{ $enquiry->tracking_code }}</dd></div><div><dt>Ward</dt><dd>{{ $enquiry->ward ?: 'Not specified' }}</dd></div><div><dt>Contact</dt><dd>{{ $enquiry->name }}{{ $enquiry->email ? ' · '.$enquiry->email : '' }}{{ $enquiry->phone ? ' · '.$enquiry->phone : '' }}</dd></div></dl>
                    <form class="staff-report-update" method="post" action="{{ route('council.reports.update', $enquiry) }}">
                        @csrf
                        @method('patch')
                        <label for="status-{{ $enquiry->id }}">Report status</label>
                        <select id="status-{{ $enquiry->id }}" name="status" required>
                            <option value="new" @selected($enquiry->status === 'new')>Received</option>
                            <option value="in_review" @selected($enquiry->status === 'in_review')>Under review</option>
                            <option value="resolved" @selected($enquiry->status === 'resolved')>Marked as resolved</option>
                            <option value="closed" @selected($enquiry->status === 'closed')>Closed</option>
                        </select>
                        <button class="button button-primary" type="submit">Save update</button>
                    </form>
                </article>
            @empty
                <div class="content-page-empty"><span class="content-page-icon">✓</span><div><h2>No reports yet</h2><p>New resident reports will appear here.</p></div></div>
            @endforelse
        </div>
        {{ $enquiries->links() }}
    </main>
</body>
</html>
