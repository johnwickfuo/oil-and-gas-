<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $serviceSlugs = array_keys(config('pricing.service_base_rates', []));
        $addonKeys = array_keys(config('pricing.addons', []));
        $locationKeys = array_keys(config('pricing.location_fees', []));

        return [
            'website' => ['nullable', 'prohibited'],
            'service_slug' => ['required', 'string', Rule::in($serviceSlugs)],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1', 'max:200'],
            'location' => ['required', 'string', Rule::in($locationKeys)],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['string', Rule::in($addonKeys)],
            'menu_preferences' => ['nullable', 'string', 'max:2000'],
            'dietary_notes' => ['nullable', 'string', 'max:2000'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.prohibited' => 'Submission blocked.',
        ];
    }
}
