<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class WardMapDataService
{
    private const CACHE_KEY = 'ward-map-data';

    private const BOUNDARIES_LAYER = 'https://services1.arcgis.com/XBhYkoXKJCRHbe7M/ArcGIS/rest/services/NGA_Ward_Boundaries/FeatureServer/0';

    private const SCHOOLS_LAYER = 'https://services3.arcgis.com/BU6Aadhn6tbBEdyk/arcgis/rest/services/Schools_in_Nigeria/FeatureServer/0';

    private const HEALTH_LAYER = 'https://services3.arcgis.com/9nfxWATFamVUTTGb/ArcGIS/rest/services/health_facilities_state_WFL1/FeatureServer/1';

    private const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';

    /**
     * @return array{boundaries: array<int, array<string, mixed>>, homes: array<int, array<string, mixed>>, schools: array<int, array<string, mixed>>, health: array<int, array<string, mixed>>, unavailable: array<int, string>, fetched_at: string, stale: bool}
     */
    public function get(): array
    {
        /** @var array{boundaries: array<int, array<string, mixed>>, homes: array<int, array<string, mixed>>, schools: array<int, array<string, mixed>>, health: array<int, array<string, mixed>>, unavailable: array<int, string>, fetched_at: string, stale: bool}|null $cached */
        $cached = Cache::get(self::CACHE_KEY);

        if (is_array($cached)) {
            return $cached;
        }

        $data = $this->fetch();
        Cache::put(self::CACHE_KEY, $data, now()->addHours(6));

        return $data;
    }

    /**
     * @return array{boundaries: array<int, array<string, mixed>>, homes: array<int, array<string, mixed>>, schools: array<int, array<string, mixed>>, health: array<int, array<string, mixed>>, unavailable: array<int, string>, fetched_at: string, stale: bool}
     */
    private function fetch(): array
    {
        $boundaries = $this->queryArcGis(
            self::BOUNDARIES_LAYER,
            "UPPER(lganame)='SHANI' AND UPPER(statename)='BORNO'",
            'wardname,wardcode,lganame,statename',
        );

        if ($boundaries === []) {
            throw new RuntimeException('No Shani ward boundaries were returned.');
        }

        $unavailable = [];
        $schools = $this->loadOptional('schools', fn (): array => $this->queryArcGis(
            self::SCHOOLS_LAYER,
            "UPPER(lganame)='SHANI' AND UPPER(statename)='BORNO'",
            'name,wardname,poi_type,education,management,category,subtype',
        ), $unavailable);
        $health = $this->loadOptional('primary health facilities', fn (): array => $this->queryArcGis(
            self::HEALTH_LAYER,
            "UPPER(lga)='SHANI' AND UPPER(state)='BORNO' AND UPPER(facility_l)='PRIMARY'",
            'facility_n,facility_1,facility_2,facility_l,ownership,ward,latitude,longitude,last_updat',
        ), $unavailable);
        $homes = $this->loadOptional('mapped homes', fn (): array => $this->loadHomes($boundaries), $unavailable);

        return [
            'boundaries' => $boundaries,
            'homes' => $homes,
            'schools' => $schools,
            'health' => $health,
            'unavailable' => $unavailable,
            'fetched_at' => now()->toIso8601String(),
            'stale' => false,
        ];
    }

    /**
     * @param  callable(): array<int, array<string, mixed>>  $callback
     * @param  array<int, string>  $unavailable
     * @return array<int, array<string, mixed>>
     */
    private function loadOptional(string $label, callable $callback, array &$unavailable): array
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            report($exception);
            $unavailable[] = $label;

            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function queryArcGis(string $layerUrl, string $where, string $outFields): array
    {
        $response = Http::timeout(20)->retry(2, 250)->get("{$layerUrl}/query", [
            'where' => $where,
            'outFields' => $outFields,
            'returnGeometry' => 'true',
            'outSR' => '4326',
            'f' => 'geojson',
        ]);

        return $this->featuresFromResponse($response);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function featuresFromResponse(Response $response): array
    {
        $response->throw();

        /** @var array<string, mixed> $result */
        $result = $response->json();

        if (isset($result['error'])) {
            throw new RuntimeException('The ArcGIS layer returned an error.');
        }

        /** @var array<int, array<string, mixed>> $features */
        $features = $result['features'] ?? [];

        return $features;
    }

    /**
     * @param  array<int, array<string, mixed>>  $features
     * @return array<int, array<string, mixed>>
     */
    private function loadHomes(array $features): array
    {
        $coordinates = [];

        foreach ($features as $feature) {
            /** @var array{type?: string, coordinates?: mixed} $geometry */
            $geometry = $feature['geometry'] ?? [];
            $rings = match ($geometry['type'] ?? null) {
                'Polygon' => $geometry['coordinates'] ?? [],
                'MultiPolygon' => array_merge(...array_values($geometry['coordinates'] ?? [])),
                default => [],
            };

            foreach ($rings as $ring) {
                if (! is_array($ring)) {
                    continue;
                }

                foreach ($ring as $point) {
                    if (is_array($point) && count($point) >= 2) {
                        $coordinates[] = $point;
                    }
                }
            }
        }

        if ($coordinates === []) {
            throw new RuntimeException('No ward coordinates were available for the homes query.');
        }

        $longitudes = array_column($coordinates, 0);
        $latitudes = array_column($coordinates, 1);
        $query = sprintf(
            '[out:json][timeout:25];way["building"~"^(house|residential|detached|semidetached_house|bungalow|apartments)$"](%s,%s,%s,%s);out center tags;',
            min($latitudes),
            min($longitudes),
            max($latitudes),
            max($longitudes),
        );

        $response = Http::timeout(30)->get(self::OVERPASS_URL, ['data' => $query]);
        $response->throw();

        /** @var array<string, mixed> $result */
        $result = $response->json();

        /** @var array<int, array<string, mixed>> $elements */
        $elements = $result['elements'] ?? [];

        return array_map(static function (array $element): array {
            /** @var array<string, mixed> $tags */
            $tags = $element['tags'] ?? [];

            return [
                'lat' => $element['center']['lat'] ?? $element['lat'] ?? null,
                'lon' => $element['center']['lon'] ?? $element['lon'] ?? null,
                'tags' => $tags,
            ];
        }, $elements);
    }
}
