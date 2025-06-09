<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . $this->user->id,
            'phone_number' => 'required|string|max:9',
            'city' => 'required|string|max:255',
        ];

        if ($this->user->customer) {
            $rules['dni'] = 'nullable|string|max:9|unique:customers,dni,' . $this->user->customer->id;
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'name'         => __('validation.attributes.name'),
            'email'        => __('validation.attributes.email_name'),
            'phone_number' => __('validation.attributes.phone_number'),
            'city'         => __('validation.attributes.city'),
            'dni'          => __('validation.attributes.dni'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
