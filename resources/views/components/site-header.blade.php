<div class="utility-bar" id="top">
    <div class="page-wrap utility-inner">
        <span><i class="status-dot"></i> Shani Local Government Area</span>
        <a class="utility-state" href="https://bornostate.gov.ng/" target="_blank" rel="noopener noreferrer"><img class="state-mark" src="{{ asset('images/borno-state-logo.png') }}" alt="Borno State Government logo"> Borno State, Nigeria</a>
        <a href="{{ route('contact') }}">Contact information <span aria-hidden="true">↗</span></a>
    </div>
</div>
<header class="site-header page-wrap">
    <a class="brand" href="{{ route('home') }}" aria-label="{{ $localGovernmentName }} home">
        <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none"><path d="M24 4 43 14v20L24 44 5 34V14L24 4Z" fill="currentColor"/><path d="m14 31 10-19 10 19M18 25h12M15 33h18" stroke="white" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        <span class="brand-name">{{ $localGovernmentName }}<small>LOCAL GOVERNMENT AREA · BORNO STATE</small></span>
    </a>
    <nav class="main-nav" aria-label="Main navigation">
        <a href="{{ route('about') }}" @if (request()->routeIs('about', 'emir-history', 'political-history')) aria-current="page" @endif>About Shani</a>
        <a href="{{ route('services') }}" @if (request()->routeIs('services')) aria-current="page" @endif>Services</a>
        <a href="{{ route('map') }}" @if (request()->routeIs('map')) aria-current="page" @endif>Ward map</a>
        <a href="{{ route('council') }}" @if (request()->routeIs('council')) aria-current="page" @endif>Council</a>
        <a href="{{ route('transparency') }}" @if (request()->routeIs('transparency')) aria-current="page" @endif>Transparency</a>
        <a href="{{ route('updates') }}" @if (request()->routeIs('updates')) aria-current="page" @endif>News & notices</a>
        <a href="{{ route('contact') }}" @if (request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
    </nav>
    <a class="header-cta" href="{{ route('home') }}#assistant">Find information <span aria-hidden="true">↗</span></a>
    <details class="mobile-menu">
        <summary>Menu <span aria-hidden="true">☰</span></summary>
        <nav aria-label="Mobile navigation">
            <a href="{{ route('home') }}" @if (request()->routeIs('home')) aria-current="page" @endif>Home</a>
            <a href="{{ route('about') }}" @if (request()->routeIs('about', 'emir-history', 'political-history')) aria-current="page" @endif>About Shani</a>
            <a href="{{ route('services') }}" @if (request()->routeIs('services')) aria-current="page" @endif>Services</a>
            <a href="{{ route('map') }}" @if (request()->routeIs('map')) aria-current="page" @endif>Ward map</a>
            <a href="{{ route('council') }}" @if (request()->routeIs('council')) aria-current="page" @endif>Council</a>
            <a href="{{ route('transparency') }}" @if (request()->routeIs('transparency', 'projects')) aria-current="page" @endif>Transparency</a>
            <a href="{{ route('updates') }}" @if (request()->routeIs('updates')) aria-current="page" @endif>News & notices</a>
            <a href="{{ route('feedback') }}" @if (request()->routeIs('feedback', 'feedback.track')) aria-current="page" @endif>Feedback</a>
            <a href="{{ route('contact') }}" @if (request()->routeIs('contact')) aria-current="page" @endif>Contact</a>
        </nav>
    </details>
</header>
