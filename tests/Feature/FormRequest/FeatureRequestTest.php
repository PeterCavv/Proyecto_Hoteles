<?php

use App\Http\Requests\Feature\FeatureRequest;
use Illuminate\Support\Facades\Validator;

it('validates a correct feature request payload', function () {
    $data = [
        'name' => 'Wi-Fi',
        'description' => 'High-speed internet access',
        'icon' => 'wifi-icon'
    ];

    $request = new FeatureRequest();
    $validator = Validator::make($data, $request->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails validation when fields are missing or invalid', function (array $invalidData) {
    $request = new FeatureRequest();
    $validator = Validator::make($invalidData, $request->rules());

    expect($validator->fails())->toBeTrue();
})->with([
    'missing name' => [['description' => 'desc', 'icon' => 'icon']],
    'missing description' => [['name' => 'Name', 'icon' => 'icon']],
    'missing icon' => [['name' => 'Name', 'description' => 'desc']],
    'name not string' => [['name' => 123, 'description' => 'desc', 'icon' => 'icon']],
    'description too long' => [[
        'name' => 'Name',
        'description' => str_repeat('a', 501),
        'icon' => 'icon'
    ]],
    'icon not string' => [['name' => 'Name', 'description' => 'desc', 'icon' => 123]],
]);
