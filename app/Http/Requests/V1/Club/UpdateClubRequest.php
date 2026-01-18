<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Club;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'phone' => ['integer', 'nullable'],
            'address' => ['array', 'nullable'],
            'address.country' => ['string', 'nullable'],
            'address.city' => ['string', 'nullable'],
            'address.district' => ['string', 'nullable'],
            'address.line' => ['string', 'nullable'],
            'working_hours' => ['array'],
            'social_links' => ['array'],
        ];
    }
}
