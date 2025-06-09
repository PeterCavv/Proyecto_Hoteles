<?php

namespace App\Http\Controllers\Public;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
{
    /**
     * Show the form for creating a new hotel.
     *
     * @return Response The rendered Inertia component for hotel creation.
     */
    public function create()
    {
        return Inertia::render('Hotel/HotelCreate');
    }

    /**
     * Handles the creation of a new hotel and its associated user.
     *
     * @param CreateHotelRequest $request The request object containing the validated data for creating a hotel.
     * @return RedirectResponse Redirects to the hotel's show route with a success message upon successful creation.
     */
    public function store(CreateHotelRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['user_name'],
            'email' => $validated['email_name'],
            'phone_number' => $validated['phone_number'],
            'city' => $validated['user_city'],
            'password' => Hash::make(Str::random(12)),
        ]);

        $user->assignRole(RoleEnum::HOTEL->value);

        $hotel = Hotel::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'city_id' => $validated['city_id'],
            'postal_code' => $validated['postal_code'],
            'user_id' => $user->id,
        ]);



        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Hotel created successfully.');
    }
}
