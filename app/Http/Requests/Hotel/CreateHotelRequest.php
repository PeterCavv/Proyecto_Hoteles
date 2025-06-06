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
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'user_name' => 'required|string|max:255',
            'email_name' => 'required|string|max:255',
            'phone_number' => 'required|digits_between:9,15',
            'user_city' => 'required|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
