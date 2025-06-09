<?php

use App\Http\Requests\Hotel\HotelRequest;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->city = City::factory()->create();
});

it('authorizes the request', function () {
    $request = new HotelRequest();

    expect($request->authorize())->toBeTrue();
});

it('passes validation with valid data', function () {
    $data = [
        'name' => 'Hotel Central',
        'description' => 'A nice hotel in the city center.',
        'location' => '123 Main Street',
        'city_id' => $this->city->id,
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
        'city_id' => $this->city->id,
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
        'city_id' => $this->city->id,
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
        'city_id' => $this->city->id,
        'postal_code' => '10115',
        'rating' => null,
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when city_id doesn\'t exist', function () {
    $data = [
        'name' => 'No Rating Hotel',
        'description' => 'Still a valid hotel',
        'location' => '789 Road',
        'city_id' => 4,
        'postal_code' => '10115',
        'rating' => null,
    ];

    $validator = Validator::make($data, (new HotelRequest())->rules());

    expect($validator->passes())->toBeFalse()
        ->and($validator->errors()->has('city_id'))->toBeTrue();;
});

