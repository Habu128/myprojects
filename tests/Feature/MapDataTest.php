<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::forget('ward-map-data');
});

test('map data comes from the backend and is cached', function () {
    $boundary = [
        'type' => 'Feature',
        'properties' => ['wardname' => 'Shani'],
        'geometry' => [
            'type' => 'Polygon',
            'coordinates' => [[
                [10.9, 10.1],
                [11.1, 10.1],
                [11.1, 10.3],
                [10.9, 10.3],
                [10.9, 10.1],
            ]],
        ],
    ];

    Http::fake([
        'services1.arcgis.com/*' => Http::response(['features' => [$boundary]]),
        'services3.arcgis.com/BU6Aadhn6tbBEdyk/*' => Http::response(['features' => []]),
        'services3.arcgis.com/9nfxWATFamVUTTGb/*' => Http::response(['features' => []]),
        'overpass-api.de/*' => Http::response(['elements' => []]),
    ]);

    $response = $this->get('/map/data')
        ->assertOk()
        ->assertJsonPath('stale', false);

    expect($response->json('boundaries.0.properties.wardname'))->toBe('Shani');
    Http::assertSentCount(4);

    $this->get('/map/data')->assertOk();
    Http::assertSentCount(4);
});

test('the map endpoint degrades gracefully when sources fail', function () {
    Http::fake(['*' => Http::response('failure', 500)]);

    $this->get('/map/data')->assertStatus(503);
});
