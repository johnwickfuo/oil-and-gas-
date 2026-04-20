<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscribeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        NewsletterSubscriber::query()->updateOrCreate(
            ['email' => strtolower(trim($data['email']))],
            [
                'name' => $data['name'] ?? null,
                'source' => $data['source'] ?? 'footer',
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ],
        );

        return back()->with('status', 'Thanks! You\'re on the list.');
    }
}
