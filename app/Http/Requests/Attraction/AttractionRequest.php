<?php

namespace App\Http\Requests\Attraction;

use Illuminate\Foundation\Http\FormRequest;

class AttractionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city_id' => 'required|exists:cities,id',
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
