<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>{{ $notice ? 'Edit notice' : 'Add notice' }} | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL STAFF · NOTICES</div>
        <h1>{{ $notice ? 'Edit notice' : 'Add a notice' }}</h1>
        <p class="content-page-lead">Only publish information that has been verified against an official source.</p>
        @if ($errors->any())
            <div class="transparency-status"><span class="updates-icon" aria-hidden="true">!</span><div><h2>Check the form</h2><p>{{ $errors->first() }}</p></div></div>
        @endif
        <form class="feedback-form" method="POST" action="{{ $notice ? route('staff.notices.update', $notice) : route('staff.notices.store') }}">
            @csrf
            @if ($notice)
                @method('PUT')
            @endif
            <div class="feedback-fields">
                <label class="feedback-message" for="title">Title<input id="title" name="title" type="text" value="{{ old('title', $notice?->title) }}" required></label>
                <label class="feedback-message" for="published_at">Publication date (optional)<input id="published_at" name="published_at" type="date" value="{{ old('published_at', $notice?->publishedDate()->format('Y-m-d')) }}"></label>
                <label class="feedback-message" for="body">Notice text<textarea id="body" name="body" rows="8" required>{{ old('body', $notice?->body) }}</textarea></label>
            </div>
            <label class="content-note" for="is_published"><input id="is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published', $notice?->is_published ?? true))> Published</label>
            <div class="staff-page-actions">
                <button class="button button-primary" type="submit">Save notice <span aria-hidden="true">→</span></button>
                <a class="text-link" href="{{ route('staff.notices.index') }}">Back to notices <span aria-hidden="true">↗</span></a>
            </div>
        </form>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
