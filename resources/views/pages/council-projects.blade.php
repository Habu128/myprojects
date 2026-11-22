<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Manage public projects | {{ $localGovernmentName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="staff-reports-heading">
            <div><div class="eyebrow"><span class="eyebrow-dot"></span> STAFF WORKSPACE</div><h1>Public projects</h1><p class="content-page-lead">Publish a project only when you can include its source record. Completed projects also need a progress evidence link.</p></div>
            <div class="staff-page-actions"><a class="button button-primary" href="{{ route('council.projects.create') }}">Add a project</a><a class="text-link" href="{{ route('council.reports.index') }}">Resident reports</a><form method="post" action="{{ route('council.reports.logout') }}">@csrf<button class="button button-secondary" type="submit">Sign out</button></form></div>
        </div>
        @if (session('project_created'))<div class="feedback-success" role="status"><strong>Project published.</strong><span>It is now visible on the public project page.</span></div>@endif
        @if (session('project_updated'))<div class="feedback-success" role="status"><strong>Project updated.</strong><span>The public project page now shows the latest information.</span></div>@endif
        @if (session('project_archived'))<div class="feedback-success" role="status"><strong>Project archived.</strong><span>The public record remains available with its archive status.</span></div>@endif
        <div class="staff-report-list">
            @forelse ($projects as $project)
                <article class="staff-report-card">
                    <div class="staff-report-meta"><strong>{{ $project->title }}</strong><span>{{ $project->ward }} · {{ $project->updated_at->format('j M Y') }}</span></div>
                    <p>{{ $project->description }}</p>
                    <dl><div><dt>Status</dt><dd>{{ str($project->status)->replace('_', ' ')->title() }}</dd></div><div><dt>Budget</dt><dd>{{ $project->budget_amount !== null ? '₦'.number_format((float) $project->budget_amount, 2) : 'Not listed' }}</dd></div><div><dt>Source</dt><dd><a href="{{ $project->source_url }}" target="_blank" rel="noopener noreferrer">{{ $project->source_title }}</a></dd></div></dl>
                    <div class="staff-page-actions"><a class="button button-secondary" href="{{ route('council.projects.edit', $project) }}">Edit project</a>@if ($project->status !== 'archived')<form method="post" action="{{ route('council.projects.archive', $project) }}" onsubmit="return confirm('Archive this project while keeping its public record?')">@csrf @method('patch')<button class="button button-secondary" type="submit">Archive</button></form>@endif</div>
                </article>
            @empty
                <div class="content-page-empty"><span class="content-page-icon">⌖</span><div><h2>No projects published yet</h2><p>Add a project with a source record to make it available to residents.</p></div></div>
            @endforelse
        </div>
        {{ $projects->links() }}
    </main>
</body>
</html>
