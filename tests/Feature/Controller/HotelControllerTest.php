<?php

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Feature;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::HOTEL->value);

    $this->user = User::factory()->create()->assignRole(RoleEnum::HOTEL->value);
    $this->actingAs($this->user);
});

it('displays a list of hotels', function () {
    Hotel::factory()->count(3)->create();

    $response = $this->get(route('hotels.index'));

    $response->assertInertia(fn (Assert $page) =>
    $page->component('Hotel/HotelIndex')
        ->has('hotels', 3)
        ->has('filters')
    );
});

it('shows a specific hotel', function () {
    $hotel = Hotel::factory()->create();

    $response = $this->get(route('hotels.show', $hotel));

    $response->assertInertia(fn (Assert $page) =>
    $page->component('Hotel/HotelShow')
        ->where('hotel.id', $hotel->id)
    );
});

it('renders the create hotel form', function () {
    $this->get(route('hotels.create'))
        ->assertInertia(fn (Assert $page) =>
        $page->component('Hotel/HotelCreate')
        );
});

it('stores a new hotel', function () {
    $city = City::factory()->create();

    $data = [
        'name' => 'Hotel Test',
        'description' => 'A nice place.',
        'location' => '123 Test Street',
        'city_id' => $city->id,
        'postal_code' => '12345',
        'user_name' => 'Test User',
        'email_name' => 'testuser@example.com',
        'phone_number' => '123456789',
        'user_city' => 'New Zeland'
    ];

    $response = $this->post(route('hotels.store'), $data);

    $response->assertRedirect();

    $this->assertDatabaseHas('hotels', [
        'name' => $data['name'],
        'city_id' => $data['city_id'],
    ]);

    $this->assertDatabaseHas('users', [
        'name' => $data['user_name'],
        'email' => $data['email_name'],
    ]);
});

it('updates a hotel', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $hotel = Hotel::factory()->create(['user_id' => $user->id]);
    $newCity = City::factory()->create();

    $newData = [
        'name' => 'Updated Hotel Name',
        'description' => 'Test',
        'location' => 'Test',
        'city_id' => $newCity->id,
        'postal_code' => '5555555',
        'rating' => '0'
    ];

    $response = $this->put(route('hotels.update', $hotel), $newData);

    $response->assertRedirect();
    $this->assertDatabaseHas('hotels', ['id' => $hotel->id, 'name' => 'Updated Hotel Name']);
});

it('deletes a hotel and its user', function () {
    $user = User::factory()->create()->assignRole(RoleEnum::HOTEL->value);
    $hotel = Hotel::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->delete(route('hotels.delete', $hotel));

    $response->assertRedirect(route('welcome'));
    $this->assertModelMissing($hotel);
    $this->assertSoftDeleted($user);
});

it('can add features to hotel', function () {
    $hotelUser = User::factory()->create();
    $hotelUser->assignRole(RoleEnum::HOTEL->value);
    $features = Feature::factory()->count(3)->create();

    $hotel = Hotel::factory()->for($hotelUser)->create();

    $featureIds = $features->pluck('id')->toArray();

    $response = $this->put(route('hotels.add-features', $hotel), [
        'features' => $featureIds,
    ]);

    $response->assertRedirect();

    foreach ($featureIds as $id) {
        expect($hotel->features()->where('feature_id', $id)->exists())->toBeTrue();
    }
});

