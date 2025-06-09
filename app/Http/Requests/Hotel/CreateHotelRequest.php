<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'location' => 'required|string|max:300',
            'city_id' => 'required|integer|exists:cities,id',
            'postal_code' => 'required|string|max:20',
            'user_name' => 'required|string|max:255',
            'email_name' => 'required|string|max:255',
            'phone_number' => 'required|digits_between:9,15',
            'user_city' => 'required|string|max:255',
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
            'user_name' => __('validation.attributes.user_name'),
            'email_name' => __('validation.attributes.email_name'),
            'phone_number' => __('validation.attributes.phone_number'),
            'user_city' => __('validation.attributes.user_city'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
