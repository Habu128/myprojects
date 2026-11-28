<?php

namespace App\Models;

use Database\Factories\ContentPageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentPage extends Model
{
    /** @use HasFactory<ContentPageFactory> */
    use HasFactory;

    protected $fillable = [
        'slug',
        'path',
        'eyebrow',
        'title',
        'description',
        'details',
        'items',
        'sources',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'sources' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return array{eyebrow: string, title: string, description: string, details: string, items: array<int, array<string, mixed>>, sources: array<int, array<string, string>>}
     */
    public function toPageData(): array
    {
        return [
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'description' => $this->description,
            'details' => $this->details ?? '',
            'items' => $this->normalizeItems($this->items),
            'sources' => $this->normalizeSources($this->sources),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        return array_values(array_filter($items, 'is_array'));
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function normalizeSources(mixed $sources): array
    {
        if (! is_array($sources)) {
            return [];
        }

        $normalized = [];

        foreach ($sources as $source) {
            if (! is_array($source)) {
                continue;
            }

            $normalizedSource = [];

            foreach ($source as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $normalizedSource[$key] = $value;
                }
            }

            $normalized[] = $normalizedSource;
        }

        return $normalized;
    }
}
