<?php

namespace App\Models;

use Database\Factories\MediaAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    /** @use HasFactory<MediaAssetFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'path',
        'alt_text',
        'category',
        'uploaded_by',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return self::urlFor($this->path);
    }

    public static function urlFor(string $path): string
    {
        if (str_starts_with($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk(self::disk())->url($path);
    }

    public static function disk(): string
    {
        return (string) config('filesystems.media_disk', 'public');
    }

    public function isStoredFile(): bool
    {
        return ! str_starts_with($this->path, 'images/');
    }
}
