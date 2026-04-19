<?php

namespace App\Http\Requests;

use App\Models\AcademyWaitlist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AcademyWaitlistRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'website' => ['nullable', 'prohibited'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'interest_level' => ['required', Rule::in(array_keys(AcademyWaitlist::INTEREST_LEVELS))],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
