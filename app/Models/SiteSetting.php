<?php

namespace App\Models;

use Database\Factories\SiteSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    /** @use HasFactory<SiteSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * @return array<string, string>
     */
    public static function values(): array
    {
        /** @var array<string, string> $settings */
        $settings = Cache::rememberForever('site-settings', fn (): array => static::query()
            ->pluck('value', 'key')
            ->map(fn (mixed $value): string => is_string($value) ? $value : '')
            ->all());

        return $settings;
    }

    public static function value(string $key, string $default = ''): string
    {
        return self::values()[$key] ?? $default;
    }

    public static function flushCache(): void
    {
        Cache::forget('site-settings');
    }
}
