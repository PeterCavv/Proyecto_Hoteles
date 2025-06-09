<?php

use App\Enums\RoleEnum;

it('returns all enum values in getValues', function () {
    $values = RoleEnum::getValues();

    expect($values)->toBeArray()
        ->and($values)->toHaveCount(3)
        ->and($values)->toContain(RoleEnum::ADMIN)
        ->and($values)->toContain(RoleEnum::CUSTOMER)
        ->and($values)->toContain(RoleEnum::HOTEL);
});

it('has correct string values', function () {
    expect(RoleEnum::ADMIN->value)->toBe('admin')
        ->and(RoleEnum::CUSTOMER->value)->toBe('customer')
        ->and(RoleEnum::HOTEL->value)->toBe('hotel');
});

it('has correct names', function () {
    expect(RoleEnum::ADMIN->name)->toBe('ADMIN')
        ->and(RoleEnum::CUSTOMER->name)->toBe('CUSTOMER')
        ->and(RoleEnum::HOTEL->name)->toBe('HOTEL');
});
