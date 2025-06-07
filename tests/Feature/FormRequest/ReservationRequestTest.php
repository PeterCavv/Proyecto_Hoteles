<?php

use App\Http\Requests\Reservation\ReservationRequest;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

beforeEach( function (){
    $this->customer = Customer::factory()->create();
    $this->hotel = Hotel::factory()->create();
    $this->room = RoomType::factory()->create();
});

it('authorizes the request', function () {
    $request = new ReservationRequest();

    expect($request->authorize())->toBeTrue();
});

it('passes validation with valid data', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->room->id,
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(2)->format('Y-m-d'),
        'price' => 100.00,
        'adults' => 2,
        'children' => 1,
    ];

    $validator = Validator::make($data, (new ReservationRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when required fields are missing', function () {
    $validator = Validator::make([], (new ReservationRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('customer_id'))->toBeTrue()
        ->and($validator->errors()->has('hotel_id'))->toBeTrue()
        ->and($validator->errors()->has('room_type_id'))->toBeTrue()
        ->and($validator->errors()->has('check_in'))->toBeTrue()
        ->and($validator->errors()->has('check_out'))->toBeTrue()
        ->and($validator->errors()->has('price'))->toBeTrue()
        ->and($validator->errors()->has('adults'))->toBeTrue()
        ->and($validator->errors()->has('children'))->toBeTrue();
});

it('fails validation when ids are not integers', function () {
    $data = [
        'customer_id' => 'not-integer',
        'hotel_id' => 'not-integer',
        'room_type_id' => 'not-integer',
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(2)->format('Y-m-d'),
        'price' => 100.00,
        'adults' => 2,
        'children' => 1,
    ];

    $validator = Validator::make($data, (new ReservationRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('customer_id'))->toBeTrue()
        ->and($validator->errors()->has('hotel_id'))->toBeTrue()
        ->and($validator->errors()->has('room_type_id'))->toBeTrue();
});

it('fails validation when check_in is not after or equal to today', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->room->id,
        'check_in' => now()->subDay()->format('Y-m-d'),
        'check_out' => now()->addDay()->format('Y-m-d'),
        'price' => 100.00,
        'adults' => 2,
        'children' => 1,
    ];

    $validator = Validator::make($data, (new ReservationRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('check_in'))->toBeTrue();
});

it('fails validation when check_out is not after check_in', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->room->id,
        'check_in' => now()->addDays(2)->format('Y-m-d'),
        'check_out' => now()->addDay()->format('Y-m-d'),
        'price' => 100.00,
        'adults' => 2,
        'children' => 1,
    ];

    $validator = Validator::make($data, (new ReservationRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('check_out'))->toBeTrue();
});

it('fails validation when price is negative', function () {
    $data = [
        'customer_id' => $this->customer->id,
        'hotel_id' => $this->hotel->id,
        'room_type_id' => $this->room->id,
        'check_in' => now()->addDay()->format('Y-m-d'),
        'check_out' => now()->addDays(2)->format('Y-m-d'),
        'price' => -100.00,
        'adults' => 2,
        'children' => 1,
    ];

    $validator = Validator::make($data, (new ReservationRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('price'))->toBeTrue();
});


