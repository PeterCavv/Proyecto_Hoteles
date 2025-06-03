<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dni' => 'nullable|string|max:20|unique:customers,dni,' . $this->route('customer'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
