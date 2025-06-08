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
}
