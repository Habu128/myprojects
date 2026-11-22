<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnquiryRequest;
use App\Models\Enquiry;
use App\Services\EnquiryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $enquiries = $this->enquiryService->getAll(
            $request->only(['status', 'search'])
        );

        return response()->json([
            'message' => 'Enquiries retrieved successfully.',
            'data' => $enquiries,
        ]);
    }

    public function __construct(
        protected EnquiryService $enquiryService
    ) {}

    public function store(StoreEnquiryRequest $request): JsonResponse
    {
        $enquiry = $this->enquiryService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Enquiry submitted successfully.',
            'data' => $enquiry,
        ], 201);
    }

    public function show(Enquiry $enquiry): JsonResponse
    {
        return response()->json([
            'message' => 'show enquiry data.',
        ]);
    }

    public function update(StoreEnquiryRequest $request, Enquiry $enquiry): JsonResponse
    {
        return response()->json([
            'message' => 'updated successfully.',
        ]);
    }

    public function destroy(Enquiry $enquiry): JsonResponse
    {
        return response()->json([
            'message' => 'enquiry deleted successfully.',
        ]);
    }
}
