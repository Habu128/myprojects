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
        $filters = $request->validate([
            'status' => ['sometimes', 'nullable', 'in:new,in_review,resolved,closed'],
            'search' => ['sometimes', 'nullable', 'string', 'max:100'],
        ]);

        $enquiries = $this->enquiryService->getAll(
            $filters,
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
            'message' => 'Enquiry retrieved successfully.',
            'data' => $enquiry,
        ]);
    }

    public function update(Request $request, Enquiry $enquiry): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:new,in_review,resolved,closed'],
        ]);

        $enquiry->update($validated);

        return response()->json([
            'message' => 'Enquiry updated successfully.',
            'data' => $enquiry->fresh(),
        ]);
    }

    public function destroy(Enquiry $enquiry): JsonResponse
    {
        $enquiry->delete();

        return response()->json(null, 204);
    }
}
