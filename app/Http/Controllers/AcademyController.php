<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademyWaitlistRequest;
use App\Models\AcademyWaitlist;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AcademyController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Academy', [
            'interestLevels' => AcademyWaitlist::INTEREST_LEVELS,
        ]);
    }

    public function joinWaitlist(AcademyWaitlistRequest $request): RedirectResponse
    {
        $data = $request->validated();

        AcademyWaitlist::query()->updateOrCreate(
            ['email' => strtolower(trim($data['email']))],
            [
                'name' => $data['name'],
                'phone' => $data['phone'],
                'interest_level' => $data['interest_level'],
                'notes' => $data['notes'] ?? null,
            ],
        );

        return back()->with('status', 'You\'re on the Academy waitlist. We\'ll be in touch!');
    }
}
