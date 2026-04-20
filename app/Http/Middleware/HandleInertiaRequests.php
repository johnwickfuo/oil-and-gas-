<?php

namespace App\Http\Middleware;

use App\Support\Settings;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'status' => fn () => $request->session()->get('status'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'site' => [
                'name' => Settings::get('site_name', 'Blue Dine Cuisines'),
                'url' => config('app.url'),
                'locale' => config('app.locale', 'en'),
                'og_image' => Settings::get('og_image', asset('images/og-default.jpg')),
                'phone' => Settings::get('phone', '+234 803 000 0000'),
                'email' => Settings::get('email', 'hello@bluedine.ng'),
                'address' => Settings::get('address', 'Port Harcourt, Rivers State, Nigeria'),
            ],
        ];
    }
}
