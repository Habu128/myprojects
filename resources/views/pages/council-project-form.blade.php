<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>{{ $project ? 'Edit project' : 'Add a project' }} | {{ $localGovernmentName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    @php($isEditingProject = $project !== null)
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> STAFF WORKSPACE</div>
        <h1>{{ $isEditingProject ? 'Edit project' : 'Add a public project' }}</h1>
        <p class="content-page-lead">Use published records for the source. Add a progress evidence link for completed work.</p>
        <form class="feedback-form project-form" method="post" action="{{ $isEditingProject ? route('council.projects.update', $project) : route('council.projects.store') }}">
            @csrf
            @if ($isEditingProject) @method('put') @endif
            <div class="feedback-fields">
                <label>Project name <input name="title" value="{{ old('title', $project?->title) }}" required maxlength="180">@error('title')<small>{{ $message }}</small>@enderror</label>
                <label>Ward
                    <select name="ward" required><option value="">Choose a ward</option>@foreach ($wards as $ward)<option value="{{ $ward }}" @selected(old('ward', $project?->ward) === $ward)>{{ $ward }}</option>@endforeach</select>
                    @error('ward')<small>{{ $message }}</small>@enderror
                </label>
                <label class="feedback-message">Project description <textarea name="description" rows="4" required maxlength="4000">{{ old('description', $project?->description) }}</textarea>@error('description')<small>{{ $message }}</small>@enderror</label>
                <label>Budget amount in naira (optional) <input type="number" name="budget_amount" min="0" step="0.01" value="{{ old('budget_amount', $project?->budget_amount) }}">@error('budget_amount')<small>{{ $message }}</small>@enderror</label>
                <label>Budget year (optional) <input type="number" name="budget_year" min="2000" max="{{ (int) date('Y') + 5 }}" value="{{ old('budget_year', $project?->budget_year) }}">@error('budget_year')<small>{{ $message }}</small>@enderror</label>
                <label>Project status
                    <select name="status" required>
                        <option value="planned" @selected(old('status', $project?->status ?? 'planned') === 'planned')>Planned</option>
                        <option value="in_progress" @selected(old('status', $project?->status) === 'in_progress')>In progress</option>
                        <option value="completed" @selected(old('status', $project?->status) === 'completed')>Completed</option>
                        <option value="paused" @selected(old('status', $project?->status) === 'paused')>Paused</option>
                        <option value="archived" @selected(old('status', $project?->status) === 'archived')>Archived</option>
                    </select>
                    @error('status')<small>{{ $message }}</small>@enderror
                </label>
                <label>Source title <input name="source_title" value="{{ old('source_title', $project?->source_title) }}" required maxlength="255" placeholder="For example: approved budget or council notice">@error('source_title')<small>{{ $message }}</small>@enderror</label>
                <label>Source link <input type="url" name="source_url" value="{{ old('source_url', $project?->source_url) }}" required maxlength="2048" placeholder="https://">@error('source_url')<small>{{ $message }}</small>@enderror</label>
                <label class="feedback-message">Progress evidence link <input type="url" name="evidence_url" value="{{ old('evidence_url', $project?->evidence_url) }}" maxlength="2048" placeholder="Required when status is completed">@error('evidence_url')<small>{{ $message }}</small>@enderror</label>
            </div>
            <p class="feedback-privacy">The public page shows the source, status and last update date. A completed status requires a link to evidence.</p>
            <div class="staff-page-actions"><button class="button button-primary" type="submit">{{ $isEditingProject ? 'Save project' : 'Publish project' }}</button><a class="button button-secondary" href="{{ route('council.projects.index') }}">Cancel</a></div>
        </form>
    </main>
</body>
</html>
