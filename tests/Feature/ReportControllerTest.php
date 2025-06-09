<?php

use App\Enums\RoleEnum;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use Barryvdh\DomPDF\Facade\Pdf;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::ADMIN->value);

    $this->admin = User::factory()->create();
    $this->admin->assignRole(RoleEnum::ADMIN->value);

    actingAs($this->admin);
});

it('generates a PDF for all user reviews', function () {
    $user = User::factory()->create();
    $hotel = Hotel::factory()->create();

    Review::factory()->count(3)->for($user)->for($hotel)->create();

    $response = get(route('reports.reviews.pdf', ['user' => $user->id]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
