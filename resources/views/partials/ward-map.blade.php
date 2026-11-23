<div>
    <!-- It is never too late to be what you might have been. - George Eliot -->
</div>
<div class="ward-map {{ $mapClass ?? '' }}" data-ward-map data-map-id="{{ $mapId }}" data-map-data-url="{{ route('map.data') }}" role="group" aria-label="Interactive map of Shani Local Government Area and its wards">
    <div class="hero-visual ward-map-canvas">
        <div class="ward-map-heading"><span class="map-heading-dot"></span><span>SHANI LGA <small>WARD MAP</small></span></div>
        <div class="ward-map-status" data-map-status role="status" aria-live="polite">Loading ward locations…</div>
        <svg class="ward-map-svg" viewBox="0 0 620 360" role="img" aria-labelledby="{{ $mapId }}-title {{ $mapId }}-description">
            <title id="{{ $mapId }}-title">Shani Local Government ward map</title>
            <desc id="{{ $mapId }}-description">Satellite imagery with mapped homes, schools, and primary health facilities across Shani's wards. Select a ward to see its locations.</desc>
        </svg>
    </div>
    <div class="ward-map-meta">
        <div class="ward-map-legend" aria-label="Map legend">
        <span><i class="legend-swatch legend-shani"></i>Shani Ward</span>
        <span><i class="legend-swatch legend-other"></i>Other wards</span>
        <span><i class="legend-home" aria-hidden="true"></i>Mapped homes</span>
        <span><i class="legend-school" aria-hidden="true">S</i>Schools</span>
        <span><i class="legend-health" aria-hidden="true">+</i>Primary health</span>
        </div>
        <div class="ward-map-selected" data-map-selected role="status" aria-live="polite">Select a ward to highlight it.</div>
        <a class="ward-map-google" data-google-maps-link href="https://www.google.com/maps/search/?api=1&amp;query=Shani%2C%20Borno%20State%2C%20Nigeria" target="_blank" rel="noopener noreferrer">Open in Google Maps <span aria-hidden="true">↗</span></a>
    </div>
    <div class="ward-map-source"><span>Imagery © <a href="https://www.esri.com/en-us/legal/terms/full-master-agreement" target="_blank" rel="noopener noreferrer">Esri</a> · Homes © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> · Schools: <a href="https://services3.arcgis.com/BU6Aadhn6tbBEdyk/arcgis/rest/services/Schools_in_Nigeria/FeatureServer/0" target="_blank" rel="noopener noreferrer">GRID3</a> · Health: <a href="https://services3.arcgis.com/9nfxWATFamVUTTGb/ArcGIS/rest/services/health_facilities_state_WFL1/FeatureServer/1" target="_blank" rel="noopener noreferrer">GRID3 / CIESIN / Nigeria HFR</a></span>@if (($mapMode ?? 'preview') === 'preview')<a class="ward-map-open" href="{{ route('map') }}">Open full map <span aria-hidden="true">↗</span></a>@endif</div>
    <p class="ward-map-caveat">Locations reflect available mapped records; home and school coverage may be incomplete and operating status should be confirmed locally. Ward boundaries are operational references, not government-certified survey lines.</p>
</div>
