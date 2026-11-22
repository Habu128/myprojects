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
            'items' => $this->items ?? [],
            'sources' => $this->sources ?? [],
        ];
    }
}
