<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    private const CACHE_KEY = 'site_settings.all';

    public static function get(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        self::flush();
    }

    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return SiteSetting::query()->pluck('value', 'key')->all();
        });
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
