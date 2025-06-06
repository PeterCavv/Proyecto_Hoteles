<?php

namespace App\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;

class FeatureRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'icon' => 'required|string|max:255'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
