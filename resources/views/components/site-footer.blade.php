@php
    $siteLogo = \App\Models\SiteSetting::value('logo_path', 'images/borno-state-logo.png');
    $footerTagline = \App\Models\SiteSetting::value('footer_tagline', 'Public information for Shani communities.');
@endphp
<footer class="site-footer">
    <a class="brand footer-brand" href="{{ route('home') }}">
        <span class="brand-mark" aria-hidden="true">✳</span>
        <span class="brand-name">{{ $localGovernmentName }}<small>LOCAL GOVERNMENT AREA · BORNO STATE</small></span>
    </a>
    <span class="footer-caption"><img class="footer-state-mark" src="{{ \App\Models\MediaAsset::urlFor($siteLogo) }}" alt="Borno State Government logo"> {{ $footerTagline }}</span>
    <nav class="footer-links" aria-label="Footer navigation">
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('services') }}">Services</a>
        <a href="{{ route('map') }}">Ward map</a>
        <a href="{{ route('council') }}">Council</a>
        <a href="{{ route('transparency') }}">Transparency</a>
        <a href="{{ route('feedback') }}">Feedback</a>
        <a href="{{ route('contact') }}">Contact</a>
    </nav>
    <a class="back-top" href="#top">Back to top <span aria-hidden="true">↑</span></a>
</footer>
