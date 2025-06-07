<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\HotelRequest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of hotels with optional filtering.
     *
     * @param Request $request The request containing data to filter.
     * @return Response The rendered Inertia component with all feature data.
     */
    public function index(Request $request)
    {
       $validated = $request->only(['city', 'name']);

       $hotels = Hotel::filter($validated)
           ->with(['features', 'reviews'])
           ->get();

        return Inertia::render('Hotel/HotelIndex', [
            'hotels' => $hotels->load('features'),
            'filters' => $validated,
        ]);
    }

    /**
     * Display the specified hotel details.
     *
     * @param Hotel $hotel The hotel instance to be displayed.
     * @return Response The rendered Inertia component with feature data.
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
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Hotel created successfully.');
    }

    /**
     * Updates the specified hotel's information.
     *
     * @param HotelRequest $request The request object containing the validated data for updating the hotel.
     * @param Hotel $hotel The hotel instance to be updated.
     * @return RedirectResponse Redirects to the hotel's show route after a successful update.
     */
    public function update(HotelRequest $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        $validated = $request->validated();

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel);
    }

    /**
     * Deletes a specified hotel and its associated user.
     *
     * @param Hotel $hotel The hotel instance to be deleted
     */
    public function delete(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);
        $user = User::findOrFail($hotel->user_id);

        $hotel->delete();

        $user->syncRoles();
        $user->delete();

        return redirect()->route('welcome');
    }

    /**
     * Associates a list of features with the specified hotel.
     *
     * @param Request $request The request object containing the validated feature data.
     * @param Hotel $hotel The hotel model to which the features will be linked.
     * @return RedirectResponse Redirects back to the previous page with a success message after adding features.
     */
    public function addFeatures(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $request->validate([
            'features' => 'required|array',
            'features.*' => 'exists:features,id',
        ]);

        $hotel->features()->sync($data['features']);

        return redirect()->route('hotels.show', $hotel);
    }
}
