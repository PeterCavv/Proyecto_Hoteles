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
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'rating' => 'nullable|integer|between:0,5'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
