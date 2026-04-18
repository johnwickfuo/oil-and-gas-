<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\GalleryImage;
use App\Models\MenuItem;
use App\Models\Service;
use App\Models\Testimonial;
use App\Support\Settings;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function home(): Response
    {
        $weekOf = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();

        return Inertia::render('Home', [
            'featuredServices' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->take(3)
                ->get(['id', 'title', 'slug', 'short_description', 'base_price', 'image']),
            'weeklyMenu' => MenuItem::query()
                ->where('is_active', true)
                ->where('week_of', $weekOf)
                ->orderBy('sort_order')
                ->take(4)
                ->get(['id', 'name', 'description', 'price', 'photo', 'category']),
            'featuredTestimonials' => Testimonial::query()
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->take(3)
                ->get(['id', 'client_name', 'quote', 'rating', 'photo']),
            'latestPosts' => BlogPost::query()
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderByDesc('published_at')
                ->take(3)
                ->get(['id', 'title', 'slug', 'excerpt', 'cover_image', 'published_at', 'category']),
            'settings' => [
                'whatsapp_number' => Settings::get('whatsapp_number'),
                'contact_phone' => Settings::get('contact_phone'),
                'service_area' => Settings::get('service_area'),
            ],
        ]);
    }

    public function about(): Response
    {
        return Inertia::render('About', [
            'settings' => [
                'whatsapp_number' => Settings::get('whatsapp_number'),
                'service_area' => Settings::get('service_area'),
            ],
        ]);
    }

    public function services(): Response
    {
        return Inertia::render('Services/Index', [
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'title', 'slug', 'short_description', 'base_price', 'image']),
        ]);
    }

    public function serviceShow(Service $service): Response
    {
        abort_unless($service->is_active, 404);

        return Inertia::render('Services/Show', [
            'service' => $service->only([
                'id', 'title', 'slug', 'short_description', 'long_description',
                'included_items', 'base_price', 'image',
            ]),
            'settings' => [
                'whatsapp_number' => Settings::get('whatsapp_number'),
            ],
        ]);
    }

    public function menu(): Response
    {
        $weekOf = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekOf->copy()->endOfWeek(Carbon::SUNDAY);

        return Inertia::render('Menu', [
            'weekOf' => $weekOf->toDateString(),
            'weekRange' => $weekOf->format('M j').' – '.$weekEnd->format('M j, Y'),
            'categories' => MenuItem::CATEGORIES,
            'items' => MenuItem::query()
                ->where('is_active', true)
                ->where('week_of', $weekOf->toDateString())
                ->orderBy('category')
                ->orderBy('sort_order')
                ->get(['id', 'name', 'description', 'price', 'photo', 'category']),
            'settings' => [
                'whatsapp_number' => Settings::get('whatsapp_number'),
            ],
        ]);
    }

    public function gallery(): Response
    {
        $gallery = GalleryImage::query()
            ->orderBy('sort_order')
            ->get(['id', 'image', 'caption', 'category'])
            ->map(fn ($g) => [
                'id' => 'g-'.$g->id,
                'image' => $g->image,
                'caption' => $g->caption,
                'category' => $g->category,
            ]);

        $menuPhotos = MenuItem::query()
            ->whereNotNull('photo')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'photo', 'category'])
            ->map(fn ($m) => [
                'id' => 'm-'.$m->id,
                'image' => $m->photo,
                'caption' => $m->name,
                'category' => $m->category,
            ]);

        $images = $gallery->concat($menuPhotos)->values();

        $categories = $images->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return Inertia::render('Gallery', [
            'images' => $images,
            'categories' => $categories,
        ]);
    }

    public function contact(): Response
    {
        return Inertia::render('Contact', [
            'details' => [
                'email' => Settings::get('contact_email'),
                'phone' => Settings::get('contact_phone'),
                'whatsapp_number' => Settings::get('whatsapp_number'),
                'instagram_url' => Settings::get('instagram_url'),
                'facebook_url' => Settings::get('facebook_url'),
                'business_address' => Settings::get('business_address'),
                'service_area' => Settings::get('service_area'),
            ],
        ]);
    }
}
