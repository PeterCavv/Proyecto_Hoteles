<?php

namespace App\Http\Requests\Attraction;

use App\Enums\AttractionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AttractionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => [ 'required', new Enum(AttractionType::class)],
            'description' => 'nullable|string|max:800',
            'city_id' => 'required|exists:cities,id',
        ];
    }
    public function authorize(): bool
    {
        return true;
    }
}
