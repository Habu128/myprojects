<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse published public projects in Shani Local Government, with ward, budget, status and source information.">
    <meta name="theme-color" content="#103d28">
    <title>Shani public projects | {{ $localGovernmentName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> PUBLIC PROJECTS</div>
        <h1>Shani project updates</h1>
        <p class="content-page-lead">See projects published by authorized council staff, with the ward, budget, status, source and latest evidence available for each one.</p>
        <div class="transparency-status"><span class="updates-icon" aria-hidden="true">⌖</span><div><h2>Read the source and check the update date</h2><p>Project information is published with a source link and update date. A project status describes the latest information entered by council staff; it is not an independent audit.</p></div></div>
        <div class="public-project-list">
            @forelse ($projects as $project)
                <article class="public-project-card">
                    <div class="public-project-heading">
                        <div><span class="card-kicker">{{ $project->ward }} ward @if ($project->budget_year) · {{ $project->budget_year }} @endif</span><h2>{{ $project->title }}</h2></div>
                        <span class="project-status project-status-{{ $project->status }}">{{ match ($project->status) {
                            'planned' => 'Planned',
                            'in_progress' => 'In progress',
                            'completed' => 'Completed',
                            'paused' => 'Paused',
                            'archived' => 'Archived',
                            default => 'Status not listed',
                        } }}</span>
                    </div>
                    <p>{{ $project->description }}</p>
                    <div class="public-project-meta">
                        @if ($project->budget_amount !== null)<span><strong>Budget:</strong> ₦{{ number_format((float) $project->budget_amount, 2) }} @if ($project->budget_year)({{ $project->budget_year }})@endif</span>@endif
                        <span><strong>Updated:</strong> {{ $project->updated_at->format('j M Y') }}</span>
                    </div>
                    <div class="public-project-links"><a href="{{ $project->source_url }}" target="_blank" rel="noopener noreferrer">{{ $project->source_title }} <span aria-hidden="true">↗</span></a>@if ($project->evidence_url)<a href="{{ $project->evidence_url }}" target="_blank" rel="noopener noreferrer">View progress evidence <span aria-hidden="true">↗</span></a>@endif</div>
                </article>
            @empty
                <div class="content-page-empty"><span class="content-page-icon">⌖</span><div><h2>No projects have been published yet</h2><p>Projects will appear here after council staff add them with a source record. Check back for updates.</p></div></div>
            @endforelse
        </div>
        {{ $projects->links() }}
        <div class="content-page-actions"><a class="text-link" href="{{ route('transparency') }}">Budgets and public records <span aria-hidden="true">↗</span></a><a class="button button-primary" href="{{ route('feedback') }}">Report a project concern <span aria-hidden="true">→</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
