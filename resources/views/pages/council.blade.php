<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Publicly sourced leadership and council directory information for Shani Local Government.">
    <meta name="theme-color" content="#103d28">
    <title>Council directory | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="content-page page-wrap">
        <div class="eyebrow"><span class="eyebrow-dot"></span> COUNCIL & ADMINISTRATION</div>
        <h1>Council directory</h1>
        <p class="content-page-lead">Available public information about Shani Local Government leadership and council contact details.</p>
        <div class="transparency-status"><span class="updates-icon" aria-hidden="true">⌂</span><div><h2>Verified council directory not available</h2><p>The leadership entry below is based on published news reports. A current council-issued staff directory and verified official contact details were not located. Confirm leadership against current council records.</p></div></div>
        <div class="content-page-grid transparency-grid">
            <article class="content-page-card content-page-card--chairman">
                <div class="chairman-gallery">
                    @foreach (range(1, 4) as $photoNumber)
                        <img class="chairman-photo" src="{{ asset("images/chairman-{$photoNumber}.jpg") }}" alt="Supplied photograph {{ $photoNumber }} showing the Shani LGA chairman" loading="lazy">
                    @endforeach
                </div>
                <p class="portrait-caption">Supplied photographs. Leadership details still require confirmation against current council records.</p>
                <span class="card-kicker">EXECUTIVE COUNCIL</span><h2>Hon. Hassan Abdu Labaki</h2><p>Identified as Executive Chairman of Shani Local Government in 2024 news coverage and quoted as the council chairman in 2025 reporting.</p><p class="directory-status">Current term dates and official council biography have not been independently confirmed here.</p>
            </article>
            <article class="content-page-card"><span class="content-page-icon">▤</span><span class="card-kicker">PUBLIC DIRECTORY</span><h2>Other council offices</h2><p>Names and verified contact details for councillors, the legislative council, departments, and principal officers are not available on this page yet.</p><a class="content-card-link" href="{{ route('feedback') }}">Request or suggest a directory update <span aria-hidden="true">→</span></a></article>
            <article class="content-page-card"><span class="content-page-icon">⌖</span><span class="card-kicker">COUNCIL SECRETARIAT</span><h2>Office address & contacts</h2><p>A verified secretariat address, phone number, email and office hours are not currently available.</p><a class="content-card-link" href="{{ route('contact') }}">View contact information status <span aria-hidden="true">→</span></a></article>
        </div>
        <section class="content-sources" aria-labelledby="council-sources-title"><h2 id="council-sources-title">Sources for the leadership entry</h2><ul><li><a href="https://www.vanguardngr.com/2024/12/tax-reform-bills-borno-lg-chairman-backs-ndume-2/" target="_blank" rel="noopener noreferrer">Vanguard, December 2024: Shani LGA chairman identified as Hassan Labaki <span aria-hidden="true">↗</span></a></li><li><a href="https://punchng.com/three-feared-dead-17-rescued-as-boat-capsizes-in-borno/" target="_blank" rel="noopener noreferrer">Punch, August 2025: NEMA statement quotes the Shani LGA chairman as Abdu Labaki <span aria-hidden="true">↗</span></a></li></ul></section>
        <div class="content-page-actions"><a class="button button-primary" href="{{ route('feedback') }}">Send a directory correction <span aria-hidden="true">→</span></a><a class="text-link" href="{{ route('transparency') }}">View budgets & public records <span aria-hidden="true">↗</span></a></div>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
