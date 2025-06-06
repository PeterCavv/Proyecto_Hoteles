<?php

use App\Http\Requests\Hotel\HotelRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('authorizes the request', function () {
    $request = new HotelRequest();

    expect($request->authorize())->toBeTrue();
});

it('passes validation with valid data', function () {
    $data = [
        'name' => 'Hotel Central',
        'description' => 'A nice hotel in the city center.',
        'location' => '123 Main Street',
        'city' => 'Paris',
        'postal_code' => '75001',
        'rating' => 4,
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when name is missing', function () {
    $data = [
        'description' => 'Missing name',
        'location' => 'Somewhere',
        'city' => 'Madrid',
        'postal_code' => '28001',
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when rating is out of range', function () {
    $data = [
        'name' => 'Bad Rating Hotel',
        'description' => 'Test description',
        'location' => '456 Street',
        'city' => 'Lisbon',
        'postal_code' => '1000',
        'rating' => 7,
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('rating'))->toBeTrue();
});

it('passes validation when rating is null', function () {
    $data = [
        'name' => 'No Rating Hotel',
        'description' => 'Still a valid hotel',
        'location' => '789 Road',
        'city' => 'Berlin',
        'postal_code' => '10115',
        'rating' => null,
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

