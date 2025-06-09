<?php

use App\Http\Requests\Customer\CustomerRequest;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Routing\Route;

beforeEach(function () {
    $this->request = new CustomerRequest();

    $route = Mockery::mock(Route::class);
    $route->shouldReceive('parameter')
        ->withArgs(function ($param, $default = null) {
            return $param === 'customer';
        })
        ->andReturn(null);

    $this->request->setRouteResolver(fn() => $route);

    $this->validationFactory = app(ValidationFactory::class);
});

it('allows nullable dni without customer', function () {
    $data = ['dni' => null];

    $validator = $this->validationFactory->make($data, $this->request->rules());
    expect($validator->passes())->toBeTrue();
});

it('validates max length dni', function () {
    $data = ['dni' => str_repeat('a', 21)];

    $validator = $this->validationFactory->make($data, $this->request->rules());
    expect($validator->fails())->toBeTrue();
});

it('includes unique rule ignoring current customer', function () {
    $route = Mockery::mock(Route::class);
    $route->shouldReceive('parameter')
        ->withArgs(function ($param, $default = null) {
            return $param === 'customer';
        })
        ->andReturn(5);

    $this->request->setRouteResolver(fn() => $route);

    $rules = $this->request->rules();

    expect($rules['dni'])->toContain('unique:customers,dni,5');
});
