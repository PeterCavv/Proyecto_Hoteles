<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\HotelRequest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class HotelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the hotels.
     *
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
       $validated = $request->only(['city', 'name']);

       $hotels = Hotel::filter($validated)
           ->with(['features', 'reviews'])
           ->get();

        return Inertia::render('Hotel/HotelIndex', [
            'hotels' => $hotels,
            'filters' => $validated,
        ]);
    }


    /**
     * Display the specified hotel.
     *
     * @param Hotel $hotel
     * @return \Inertia\Response
     */
    public function show(Hotel $hotel)
    {
        $hotel->load(['features', 'reviews.user']);

        return Inertia::render('Hotel/HotelShow', [
            'hotel' => $hotel,
        ]);
    }

    /**
     * Show the form for creating a new hotel.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Hotel/HotelCreate');
    }

    /**
     * Store a newly created hotel in storage.
     *
     * @param \App\Http\Requests\HotelRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Hotel created successfully.');
    }

    public function update(HotelRequest $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        $validated = $request->validated();

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel);
    }

    public function delete(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);
        $user = User::findOrFail($hotel->user_id);

        $hotel->delete();

        $user->syncRoles();
        $user->delete();

        return redirect()->route('welcome');
    }
}
