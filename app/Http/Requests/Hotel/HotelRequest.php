<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'location' => 'required|string|max:300',
            'city_id' => 'required|integer|exists:cities,id',
            'postal_code' => 'required|string|max:20',
            'rating' => 'nullable|integer|between:0,5'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('validation.attributes.name'),
            'description' => __('validation.attributes.description'),
            'location' => __('validation.attributes.location'),
            'city' => __('validation.attributes.city'),
            'postal_code' => __('validation.attributes.postal_code'),
            'rating' => __('validation.attributes.rating'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
