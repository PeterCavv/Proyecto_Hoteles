<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
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
           ->with(['features', 'reviews']);

        return Inertia::render('Hotels/Index', [
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

        return Inertia::render('Hotels/Show', [
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
        return Inertia::render('Hotels/Create');
    }

    /**
     * Store a newly created hotel in storage.
     *
     * @param \App\Http\Requests\HotelRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HotelRequest $request)
    {
        $validated = $request->validated();

        $hotel = Hotel::create($validated);

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
}
