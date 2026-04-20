<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Seo
{
    public const SITE_NAME = 'Blue Dine Cuisines';

    public const DEFAULT_DESCRIPTION = 'Blue Dine Cuisines — private chef, meal prep and small chops catering in Port Harcourt. Intimate dinners, healthy meal delivery, and event catering by Chef Eureka.';

    public const DEFAULT_KEYWORDS = 'private chef Port Harcourt, meal prep Port Harcourt, small chops catering Port Harcourt, healthy meal delivery Port Harcourt';

    public static function build(array $overrides = []): array
    {
        $name = Settings::get('site_name', self::SITE_NAME);
        $defaultImage = Settings::get('og_image', asset('images/og-default.jpg'));

        $title = $overrides['title'] ?? $name;
        $fullTitle = ($overrides['title'] ?? null)
            ? $overrides['title'].' | '.$name
            : $name.' — Private Chef & Meal Prep in Port Harcourt';

        $description = $overrides['description'] ?? self::DEFAULT_DESCRIPTION;
        $canonical = $overrides['canonical'] ?? URL::current();
        $image = $overrides['image'] ?? $defaultImage;
        $type = $overrides['type'] ?? 'website';

        return [
            'site_name' => $name,
            'title' => $title,
            'full_title' => $fullTitle,
            'description' => Str::limit(strip_tags((string) $description), 300, ''),
            'canonical' => $canonical,
            'image' => $image,
            'type' => $type,
            'keywords' => $overrides['keywords'] ?? self::DEFAULT_KEYWORDS,
            'structured_data' => $overrides['structured_data'] ?? null,
            'breadcrumbs' => $overrides['breadcrumbs'] ?? null,
        ];
    }
}
