<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\Recipe;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate sitemap.xml covering all public Blue Dine pages.';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        $staticRoutes = [
            ['home', 'daily', 1.0],
            ['about', 'monthly', 0.8],
            ['services.index', 'weekly', 0.9],
            ['menu', 'weekly', 0.8],
            ['gallery', 'monthly', 0.6],
            ['blog.index', 'weekly', 0.7],
            ['recipes.index', 'weekly', 0.7],
            ['resources.index', 'monthly', 0.6],
            ['academy', 'monthly', 0.5],
            ['contact', 'monthly', 0.7],
            ['pricing', 'monthly', 0.6],
            ['booking.create', 'monthly', 0.7],
        ];

        foreach ($staticRoutes as [$name, $freq, $priority]) {
            $sitemap->add(
                Url::create(route($name, [], false))
                    ->setChangeFrequency($freq)
                    ->setPriority($priority)
                    ->setLastModificationDate(Carbon::now()),
            );
        }

        Service::query()->where('is_active', true)->get()->each(function (Service $service) use ($sitemap) {
            $sitemap->add(
                Url::create(route('services.show', $service->slug, false))
                    ->setChangeFrequency('monthly')
                    ->setPriority(0.8)
                    ->setLastModificationDate($service->updated_at ?? Carbon::now()),
            );
        });

        BlogPost::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->each(function (BlogPost $post) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('blog.show', $post->slug, false))
                        ->setChangeFrequency('monthly')
                        ->setPriority(0.7)
                        ->setLastModificationDate($post->updated_at ?? $post->published_at),
                );
            });

        Recipe::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get()
            ->each(function (Recipe $recipe) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('recipes.show', $recipe->slug, false))
                        ->setChangeFrequency('monthly')
                        ->setPriority(0.7)
                        ->setLastModificationDate($recipe->updated_at ?? $recipe->published_at),
                );
            });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml generated at '.public_path('sitemap.xml'));

        return self::SUCCESS;
    }
}
