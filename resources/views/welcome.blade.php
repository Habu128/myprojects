<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Public information about Shani Local Government Area, including wards, local administration, public records and resident feedback.">
    <meta name="theme-color" content="#103d28">
    <title>{{ $localGovernmentName }} | Public Information</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />

    <main id="home">
        <section class="hero page-wrap hero--text">
            <div class="hero-copy"><div class="eyebrow"><span class="eyebrow-dot"></span> {{ $settings['hero_eyebrow'] ?? 'SHANI · BORNO STATE' }}</div><h1>{{ $settings['hero_title_line_1'] ?? 'Shani Local' }}<br><span>{{ $settings['hero_title_line_2'] ?? 'Government Area' }}</span></h1><p class="hero-description">{{ $settings['hero_description'] ?? '' }}</p><div class="hero-actions"><a class="button button-primary" href="{{ route('services') }}">Explore services <span aria-hidden="true">→</span></a><a class="text-link" href="{{ route('map') }}">Explore the ward map <span aria-hidden="true">↗</span></a></div><div class="hero-note"><span class="note-icon">✦</span><span>{{ $settings['hero_note'] ?? '' }}</span></div></div>
        </section>

        <section class="quick-links" aria-label="Popular links"><div class="quick-inner page-wrap"><div class="quick-intro"><span class="quick-icon">✳</span><span><strong>Public information</strong><small>Commonly requested pages</small></span></div><a class="quick-link" href="{{ route('services') }}"><span class="quick-number">01</span><span>Find a service</span><span class="quick-arrow">↗</span></a><a class="quick-link" href="{{ route('transparency') }}"><span class="quick-number">02</span><span>Budgets & projects</span><span class="quick-arrow">↗</span></a><a class="quick-link" href="{{ route('feedback') }}"><span class="quick-number">03</span><span>Report an issue</span><span class="quick-arrow">↗</span></a></div></section>

        <section class="about-section page-wrap" id="about"><div class="about-visual"><div class="about-sun"></div><div class="about-land land-back"></div><div class="about-land land-front"></div><div class="about-seal"><span>SHANI</span><b>✳</b><small>BORNO STATE · NIGERIA</small></div><span class="about-caption"><i></i> SHANI · BORNO STATE</span></div><div class="about-copy"><div class="eyebrow"><span class="eyebrow-dot"></span> ABOUT THE LGA</div><h2>{{ $settings['about_title_line_1'] ?? 'About Shani' }}<br><span>{{ $settings['about_title_line_2'] ?? 'Local administration' }}</span></h2><p class="about-lead">{{ $settings['about_lead'] ?? '' }}</p><p class="about-detail">{{ $settings['about_detail'] ?? '' }}</p><a class="underlined-link" href="{{ route('services') }}">View local services <span aria-hidden="true">→</span></a></div></section>

        <section class="services-section" id="services"><div class="page-wrap services-wrap"><div class="section-heading"><div><div class="eyebrow"><span class="eyebrow-dot"></span> LOCAL GOVERNMENT RESPONSIBILITIES</div><h2>Public services<br><span>and administration</span></h2></div><p>The pages below summarise local government responsibilities and provide links to public information. Confirm current requirements with the responsible office.</p></div><div class="services-grid"><a class="service-card service-green" href="{{ route('contact') }}"><span class="service-icon">⌂</span><span class="card-kicker">COMMUNITY</span><h3>Community services</h3><p>Find information about local facilities, community support, and public amenities.</p><span class="service-arrow">↗</span></a><a class="service-card service-sand" href="{{ route('contact') }}"><span class="service-icon">▤</span><span class="card-kicker">CIVIC INFORMATION</span><h3>Local administration</h3><p>Learn how to reach the appropriate office for local government enquiries.</p><span class="service-arrow">↗</span></a><a class="service-card service-blue" href="{{ route('updates') }}"><span class="service-icon">◷</span><span class="card-kicker">PUBLIC UPDATES</span><h3>Notices & announcements</h3><p>Keep up with public notices and updates shared by the council.</p><span class="service-arrow">↗</span></a></div><p class="content-note">Service availability, fees and application requirements should be confirmed with the responsible government office.</p></div></section>

        <section class="updates-section page-wrap" id="updates">
            <div class="updates-heading"><div><div class="eyebrow"><span class="eyebrow-dot"></span> PUBLIC NOTICES</div><h2>News & <span>announcements.</span></h2></div><span class="updates-tag">PUBLIC INFORMATION</span></div>
            @if ($notices->isNotEmpty())
                <div class="content-page-grid updates-grid">
                    @foreach ($notices as $notice)
                        <article class="content-page-card"><span class="card-kicker">{{ $notice->publishedDate()->format('j M Y') }}</span><h2>{{ $notice->title }}</h2><p>{{ \Illuminate\Support\Str::limit($notice->body, 180) }}</p></article>
                    @endforeach
                </div>
                <div class="content-page-actions"><a class="underlined-link" href="{{ route('updates') }}">Read all notices <span aria-hidden="true">→</span></a></div>
            @else
                <div class="updates-empty"><span class="updates-icon">✳</span><div><h3>No notices are currently listed</h3><p>This page will list public notices when they are available. For urgent matters, contact the relevant government authority.</p></div><a class="underlined-link" href="{{ route('contact') }}">Contact the council <span aria-hidden="true">→</span></a></div>
            @endif
        </section>

        <section class="assistant-section" id="assistant"><div class="assistant-inner page-wrap"><div class="assistant-copy"><div class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> SITE INFORMATION</div><h2>Search the site<br><span>or ask a question</span></h2><p>Use this tool to locate information on this website. Verify official requirements, fees and current policies with the responsible office.</p><ul class="assistant-points"><li><span>✓</span> Search public information</li><li><span>✓</span> Find relevant pages</li><li><span>✓</span> Confirm details with the council</li></ul><a class="button button-light" href="#assistant-preview">Open site search <span aria-hidden="true">↗</span></a><small class="assistant-caption">{{ $assistantConfigured ? 'The information assistant is available.' : 'The information assistant is currently unavailable.' }}</small></div>
            <div class="assistant-preview" id="assistant-preview"><div class="preview-top"><span class="preview-avatar">✳</span><div><strong>Shani information</strong><small><span class="online-dot {{ $assistantConfigured ? 'online-dot-ready' : 'online-dot-offline' }}"></span>{{ $assistantConfigured ? 'Available' : 'Unavailable' }}</small></div><span class="preview-menu" aria-hidden="true">•••</span></div><div class="preview-content" id="chat-messages" role="log" aria-live="polite" aria-label="Assistant conversation"><div class="preview-greeting">Welcome.<br><span>What would you like to find?</span></div><div class="preview-answer"><span class="answer-star">✳</span><span>Search for information on this website. Confirm current requirements, fees, dates and policies with the responsible office.</span></div></div><div class="preview-suggestions"><button type="button" data-ask="What services can I find on this website?" {{ $assistantConfigured ? '' : 'disabled' }}>Find a service</button><button type="button" data-ask="How can I contact the council?" {{ $assistantConfigured ? '' : 'disabled' }}>Contact information</button></div><form class="preview-form" action="{{ route('assistant.chat') }}" method="post" data-chat-form><input type="text" name="message" id="assistant-message" maxlength="1200" placeholder="{{ $assistantConfigured ? 'Ask a question…' : 'Search unavailable' }}" aria-label="Ask the local information assistant a question" autocomplete="off" {{ $assistantConfigured ? '' : 'disabled' }}><button type="submit" aria-label="Send question" {{ $assistantConfigured ? '' : 'disabled' }}>↑</button></form><div class="preview-disclaimer">Website information may not reflect the latest council decisions. Confirm current details with the responsible office.</div></div>
        </div></section>

        <section class="contact-section page-wrap" id="contact"><div class="contact-intro"><div class="eyebrow"><span class="eyebrow-dot"></span> GET IN TOUCH</div><h2>Contact<br><span>information</span></h2><p>For official information, service requirements, and urgent enquiries, contact the appropriate council office.</p></div><div class="contact-card"><div class="contact-card-top"><span class="contact-icon">⌖</span><span class="card-kicker">SHANI LOCAL GOVERNMENT</span></div><h3>Council contact details</h3><dl><div><dt>Address</dt><dd>{{ ($settings['contact_address'] ?? '') ?: 'Not published' }}</dd></div><div><dt>Phone</dt><dd>{{ ($settings['contact_phone'] ?? '') ?: 'Not published' }}</dd></div><div><dt>Email</dt><dd>{{ ($settings['contact_email'] ?? '') ?: 'Not published' }}</dd></div><div><dt>Office hours</dt><dd>{{ ($settings['contact_hours'] ?? '') ?: 'Not published' }}</dd></div></dl><p class="contact-note">{{ $settings['contact_note'] ?? '' }}</p></div></section>

        <section class="closing-section page-wrap"><div class="closing-mark">✳</div><div><div class="eyebrow"><span class="eyebrow-dot"></span> SHANI LOCAL GOVERNMENT AREA</div><h2>Public records and information for <span>Shani LGA.</span></h2></div><a class="button button-primary" href="{{ route('services') }}">Explore services <span aria-hidden="true">→</span></a></section>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
