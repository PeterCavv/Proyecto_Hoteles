<?php

use App\Http\Requests\Attraction\AttractionRequest;
use App\Models\City;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('authorizes the request', function () {
    $request = new AttractionRequest();

    expect($request->authorize())->toBeTrue();
});

it('passes validation with valid data', function () {
    $city = City::factory()->create();

    $data = [
        'name' => 'Eiffel Tower',
        'description' => 'Iconic Paris attraction',
        'city_id' => $city->id,
    ];

    $validator = Validator::make($data, (new AttractionRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when name is missing', function () {
    $city = City::factory()->create();

    $data = [
        'description' => 'No name provided',
        'city_id' => $city->id,
    ];

    $validator = Validator::make($data, (new AttractionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when city_id is missing', function () {
    $data = [
        'name' => 'Attraction without city',
        'description' => 'Floating in nowhere',
    ];

    $validator = Validator::make($data, (new AttractionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('city_id'))->toBeTrue();
});

it('fails validation when city_id does not exist', function () {
    $data = [
        'name' => 'Ghost Attraction',
        'description' => 'City does not exist',
        'city_id' => 999999, // assuming it doesn't exist
    ];

    $validator = Validator::make($data, (new AttractionRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('city_id'))->toBeTrue();
});

