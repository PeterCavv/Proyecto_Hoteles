<?php

namespace Tests\Feature\Model;

use App\Enums\RoleEnum;
use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Like;
use App\Models\Review;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('has one customer', function () {
    $customer = Customer::factory()->create(['user_id' => $this->user->id]);

    expect($this->user->customer)->toBeInstanceOf(Customer::class)
        ->and($this->user->customer->id)->toBe($customer->id);
});

it('has one hotel', function () {
    $hotel = Hotel::factory()->create(['user_id' => $this->user->id]);

    expect($this->user->hotel)->toBeInstanceOf(Hotel::class)
        ->and($this->user->hotel->id)->toBe($hotel->id);
});

it('has many reviews', function () {
    $review1 = Review::factory()->create(['user_id' => $this->user->id]);
    $review2 = Review::factory()->create(['user_id' => $this->user->id]);

    expect($this->user->reviews)->toHaveCount(2)
        ->and($this->user->reviews->pluck('id'))->toContain($review1->id)
        ->and($this->user->reviews->pluck('id'))->toContain($review2->id);
});

it('has many likes', function () {
    $like1 = Like::factory()->create(['user_id' => $this->user->id]);
    $like2 = Like::factory()->create(['user_id' => $this->user->id]);

    expect($this->user->likes)->toHaveCount(2)
        ->and($this->user->likes->pluck('id'))->toContain($like1->id)
        ->and($this->user->likes->pluck('id'))->toContain($like2->id);
});

it('can block and unblock users', function () {
    $user2 = User::factory()->create();

    $this->user->block($user2);
    $this->user->refresh();

    expect($this->user->blockedFriends->pluck('id'))->toContain($user2->id)
        ->and($this->user->isBlockedBy($user2))->toBeFalse()
        ->and($user2->isBlockedBy($this->user))->toBeFalse();

    $this->user->unblock($user2);
    $this->user->refresh();

    expect($this->user->blockedFriends)->toHaveCount(0)
        ->and($user2->isBlockedBy($this->user))->toBeFalse();
});

it('can detect if it is a customer or hotel owner', function () {
    Role::findOrCreate('customer');
    Role::findOrCreate('hotel');

    expect($this->user->isCustomer())->toBeFalse()
        ->and($this->user->isHotel())->toBeFalse();

    Customer::factory()->create(['user_id' => $this->user->id]);
    $this->user->assignRole('customer');
    $this->user->refresh();
    expect($this->user->isCustomer())->toBeTrue();

    Hotel::factory()->create(['user_id' => $this->user->id]);
    $this->user->syncRoles(['hotel']);
    $this->user->refresh();
    expect($this->user->isHotel())->toBeTrue();
});

it('detects if the user is admin', function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    $this->user->assignRole(RoleEnum::ADMIN->value);
    $this->user->refresh();

    expect($this->user->isAdmin())->toBeTrue();
});


it('returns the correct role name attribute', function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);
    $this->user->assignRole(RoleEnum::ADMIN->value);
    $this->user->refresh();

    expect($this->user->role_name)->toBe('admin');
});

it('can block and unblock a collection of users', function () {
    $users = User::factory()->count(2)->create();
    $this->user->block($users);
    $this->user->refresh();

    expect($this->user->blockedFriends->pluck('id'))->toContain($users[0]->id)
        ->and($this->user->blockedFriends->pluck('id'))->toContain($users[1]->id);

    $this->user->unblock($users);
    $this->user->refresh();

    expect($this->user->blockedFriends)->toHaveCount(0);
});

it('can block and unblock users from an array of ids', function () {
    $users = User::factory()->count(2)->create();
    $arrayOfUsers = $users->map(fn($u) => ['id' => $u->id])->toArray();

    $this->user->block($arrayOfUsers);
    $this->user->refresh();

    expect($this->user->blockedFriends->pluck('id'))->toContain($users[0]->id)
        ->and($this->user->blockedFriends->pluck('id'))->toContain($users[1]->id);

    $this->user->unblock($arrayOfUsers);
    $this->user->refresh();

    expect($this->user->blockedFriends)->toHaveCount(0);
});


