<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\NoticeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notice extends Model
{
    /** @use HasFactory<NoticeFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'published_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Notice>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function publishedDate(): CarbonInterface
    {
        $date = $this->published_at ?? $this->created_at;

        return $date instanceof CarbonInterface
            ? $date
            : Carbon::parse($date ?? now());
    }
}
