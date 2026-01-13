<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Club;

use Illuminate\Foundation\Http\FormRequest;

final class StoreClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'logo_path' => ['image', 'required'],
            'cover_path' => ['image'],
            'phone' => ['integer', 'required'],
            'address' => ['array', 'required'],
            'address.country' => ['string', 'required'],
            'address.city' => ['string', 'required'],
            'address.district' => ['string', 'required'],
            'address.line' => ['string', 'required'],
            'working_hours' => ['array'],
            'social_links' => ['array'],
        ];
    }
}
