<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Send feedback or report a local service issue to Shani Local Government.">
    <meta name="theme-color" content="#103d28">
    <title>Resident feedback | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> RESIDENT FEEDBACK</div>
        <h1>Send feedback or report an issue.</h1>
        <p class="content-page-lead">Submit a non-urgent service enquiry or suggest a correction to information on this website. Select a ward if relevant.</p>
        @if (session('tracking_code'))
            <div class="feedback-success" role="status"><strong>Your report has been received.</strong><span>Save this reference code to check its status later: <strong>{{ session('tracking_code') }}</strong></span></div>
        @endif
        <form class="feedback-form" method="post" action="{{ route('feedback.store') }}">
            @csrf
            <div class="feedback-fields">
                <label>Name (optional) <input name="name" autocomplete="name" value="{{ old('name') }}" maxlength="255">@error('name')<small>{{ $message }}</small>@enderror</label>
                <label>Email (optional) <input type="email" name="email" autocomplete="email" value="{{ old('email') }}" maxlength="255">@error('email')<small>{{ $message }}</small>@enderror</label>
                <label>Phone (optional) <input name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}" maxlength="20">@error('phone')<small>{{ $message }}</small>@enderror</label>
                <label>What is this about?
                    <select name="topic" required>
                        <option value="">Choose a topic</option>
                        <option value="service" @selected(old('topic') === 'service')>Council service</option>
                        <option value="community-facility" @selected(old('topic') === 'community-facility')>Community facility</option>
                        <option value="road-or-water" @selected(old('topic') === 'road-or-water')>Road or water issue</option>
                        <option value="health" @selected(old('topic') === 'health')>Health</option>
                        <option value="education" @selected(old('topic') === 'education')>Education</option>
                        <option value="other" @selected(old('topic') === 'other')>Other</option>
                    </select>
                    @error('topic')<small>{{ $message }}</small>@enderror
                </label>
                <label>Ward (optional)
                    <select name="ward">
                        <option value="">Choose a ward</option>
                        @foreach ($wards as $ward)
                            <option value="{{ $ward }}" @selected(old('ward') === $ward)>{{ $ward }}</option>
                        @endforeach
                    </select>
                    @error('ward')<small>{{ $message }}</small>@enderror
                </label>
                <label class="feedback-message">Your message <textarea name="message" rows="6" required maxlength="5000">{{ old('message') }}</textarea>@error('message')<small>{{ $message }}</small>@enderror</label>
            </div>
            <p class="feedback-privacy">You can report without giving your name or email. If you provide contact details, they are stored with your report. Do not include passwords, financial details, or urgent emergency reports.</p>
            <button class="button button-primary" type="submit">Submit feedback <span aria-hidden="true">→</span></button>
            <p class="feedback-emergency">For an immediate emergency, contact the appropriate emergency service directly.</p>
        </form>
        <section class="feedback-track" aria-labelledby="track-report-title">
            <h2 id="track-report-title">Already reported an issue?</h2>
            <p>Enter the reference code you received after submitting your report.</p>
            <form method="post" action="{{ route('feedback.track') }}" class="feedback-track-form">
                @csrf
                <label for="tracking_code">Report reference</label>
                <input id="tracking_code" name="tracking_code" value="{{ old('tracking_code') }}" minlength="10" maxlength="10" pattern="[A-Za-z0-9]{10}" required>
                @error('tracking_code')<small>{{ $message }}</small>@enderror
                <button class="button button-primary" type="submit">Check status</button>
            </form>
        </section>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
