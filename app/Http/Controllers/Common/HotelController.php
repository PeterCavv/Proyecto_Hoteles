<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
{
    /**
     * Display a listing of the hotels with optional filters by city or name.
     *
     * This method retrieves hotels filtered by the provided city and/or name,
     * including their related features and reviews. It returns an Inertia view
     * with the list of hotels and the applied filters.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $validated = $request->only(['city_id', 'name']);

        $hotels = Hotel::filter($validated)
            ->with(['features', 'reviews'])
            ->get();

        return Inertia::render('Hotel/HotelIndex', [
            'hotels' => $hotels->load('features'),
            'filters' => $validated,
        ]);
    }

    /**
     * Display the details of a specific hotel.
     *
     * This method retrieves a single hotel with its features and the users
     * associated with its reviews. It returns an Inertia view with all
     * necessary data for the hotel detail page.
     *
     * @param Hotel $hotel
     * @return Response
     */
    public function show(Hotel $hotel)
    {
        $hotel->load(['features', 'reviews.user']);

        return Inertia::render('Hotel/HotelShow', [
            'hotel' => $hotel,
        ]);
    }
}
