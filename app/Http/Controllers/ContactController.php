<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Support\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = Settings::get('contact_email', config('mail.from.address'));

        if ($recipient) {
            Mail::to($recipient)->send(new ContactFormMail($data));
        }

        return back()->with('status', 'Thanks for reaching out — we\'ll reply within one business day.');
    }
}
