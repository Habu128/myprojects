<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#103d28">
    <title>Edit {{ $contentPage->title }} | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL STAFF · {{ $contentPage->path }}</div>
        <h1>Edit {{ $contentPage->title }}</h1>
        <p class="content-page-lead">Items and sources are JSON arrays. Each item is an object with a title and description; optional keys include route, link_text, kicker, status and photos.</p>
        @if ($errors->any())
            <div class="transparency-status"><span class="updates-icon" aria-hidden="true">!</span><div><h2>Check the form</h2><p>{{ $errors->first() }}</p></div></div>
        @endif
        <form class="feedback-form" method="POST" action="{{ route('staff.content.update', $contentPage) }}">
            @csrf
            @method('PUT')
            <div class="feedback-fields">
                <label for="eyebrow">Eyebrow<input id="eyebrow" name="eyebrow" type="text" value="{{ old('eyebrow', $contentPage->eyebrow) }}" required></label>
                <label for="title">Title<input id="title" name="title" type="text" value="{{ old('title', $contentPage->title) }}" required></label>
                <label class="feedback-message" for="description">Lead paragraph<textarea id="description" name="description" rows="3" required>{{ old('description', $contentPage->description) }}</textarea></label>
                <label class="feedback-message" for="details">Closing note<textarea id="details" name="details" rows="3">{{ old('details', $contentPage->details) }}</textarea></label>
                <label class="feedback-message" for="items">Items (JSON)<textarea id="items" name="items" rows="16" spellcheck="false" required>{{ old('items', json_encode($contentPage->items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea></label>
                <label class="feedback-message" for="sources">Sources (JSON)<textarea id="sources" name="sources" rows="10" spellcheck="false" required>{{ old('sources', json_encode($contentPage->sources, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}</textarea></label>
            </div>
            <label class="content-note" for="is_published"><input id="is_published" name="is_published" type="checkbox" value="1" @checked(old('is_published', $contentPage->is_published))> Published</label>
            <p class="feedback-privacy">Unpublished pages return a 404 to visitors. Preview a page at <strong>{{ $contentPage->path }}</strong> after saving.</p>
            <div class="staff-page-actions">
                <button class="button button-primary" type="submit">Save changes <span aria-hidden="true">→</span></button>
                <a class="text-link" href="{{ route('staff.content.index') }}">Back to content <span aria-hidden="true">↗</span></a>
            </div>
        </form>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
