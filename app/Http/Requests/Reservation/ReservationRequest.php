<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|integer|exists:customers,id',
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'price' => 'required|numeric|decimal:0,2|min:0',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
