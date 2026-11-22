<?php

namespace App\Http\Controllers;

use App\Services\WardMapDataService;
use Illuminate\Http\JsonResponse;
use Throwable;

class MapDataController extends Controller
{
    public function __invoke(WardMapDataService $wardMapDataService): JsonResponse
    {
        try {
            $data = $wardMapDataService->get();
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Ward map data is temporarily unavailable.',
            ], 503);
        }

        return response()->json($data)
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
