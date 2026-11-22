<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore Shani Local Government Area on satellite imagery with interactive ward boundaries.">
    <meta name="theme-color" content="#103d28">
    <title>Shani Ward Map | {{ $localGovernmentName }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-site-header :local-government-name="$localGovernmentName" />
    <main class="page-wrap map-page">
        <div class="eyebrow"><span class="eyebrow-dot"></span> SHANI LOCAL GOVERNMENT AREA</div>
        <h1>Shani LGA <span>ward map</span></h1>
        <p class="content-page-lead">Select a ward to view mapped settlements, schools and primary healthcare facilities. The map links to the corresponding Google Maps view.</p>
        @include('partials.ward-map', ['mapId' => 'full-shani-ward-map', 'mapClass' => 'ward-map-full', 'mapMode' => 'full'])
        <p class="map-method-note">Map source: Esri satellite imagery and GRID3 / eHealth Africa operational ward boundaries (CC BY 4.0). Boundaries are indicative and may differ from government survey records.</p>
    </main>
    <x-site-footer :local-government-name="$localGovernmentName" />
</body>
</html>
