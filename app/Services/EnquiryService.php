<?php

namespace App\Services;

use App\Models\Enquiry;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EnquiryService
{
    /** @param array<string, mixed> $data */
    public function create(array $data): Enquiry
    {
        return Enquiry::create($data);
    }

    /**
     * @param  array{status?: string|null, search?: string|null}  $filters
     * @return LengthAwarePaginator<int, Enquiry>
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Enquiry::query()
            ->when($filters['status'] ?? null, function (Builder $query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['search'] ?? null, function (Builder $query, $search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }
}
