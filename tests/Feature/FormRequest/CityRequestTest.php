<?php

use App\Http\Requests\City\CityRequest;
use Illuminate\Support\Facades\Validator;

it('authorizes the request', function () {
    $request = new CityRequest();

    expect($request->authorize())->toBeTrue();
});

it('passes validation with valid data', function () {
    $data = [
        'name' => 'Barcelona',
        'country' => 'Spain',
    ];

    $validator = Validator::make($data, (new CityRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when name is missing', function () {
    $data = [
        'country' => 'Spain',
    ];

    $validator = Validator::make($data, (new CityRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

it('fails validation when country is missing', function () {
    $data = [
        'name' => 'Barcelona',
    ];

    $validator = Validator::make($data, (new CityRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('country'))->toBeTrue();
});

it('fails validation when name exceeds max length', function () {
    $data = [
        'name' => str_repeat('a', 256),
        'country' => 'Spain',
    ];

    $validator = Validator::make($data, (new CityRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('name'))->toBeTrue();
});

