<?php

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->request = new UpdateUserRequest();

    $this->request->user = $this->user;
});

it('authorizes the request', function () {
    expect($this->request->authorize())->toBeTrue();
});

it('validates correctly without customer', function () {
    $this->request->user = $this->user;
    $this->user->customer = null;

    $data = [
        'name' => 'Pit',
        'email' => 'pit@example.com',
        'phone_number' => '123456789',
        'city' => 'Barcelona',
    ];

    $validator = Validator::make($data, $this->request->rules());

    expect($validator->passes())->toBeTrue();

    $invalidData = $data;
    unset($invalidData['name']);

    $validatorFail = Validator::make($invalidData, $this->request->rules());
    expect($validatorFail->fails())->toBeTrue();
});

it('validates correctly with customer dni', function () {
    $customer = Customer::factory()->make(['id' => 5]);
    $this->user->customer = $customer;

    $this->request->user = $this->user;

    $data = [
        'name' => 'Pit',
        'email' => 'pit@example.com',
        'phone_number' => '123456789',
        'city' => 'Barcelona',
        'dni' => '12345678Z',
    ];

    $validator = Validator::make($data, $this->request->rules());

    expect($validator->passes())->toBeTrue();

    $invalidData = $data;
    $invalidData['dni'] = '1234567890';

    $validatorFail = Validator::make($invalidData, $this->request->rules());
    expect($validatorFail->fails())->toBeTrue();
});

