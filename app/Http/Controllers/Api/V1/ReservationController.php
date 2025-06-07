<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\ReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    /**
     * Display a listing of reservations.
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])->get();
        } elseif (auth()->user()->isCustomer()) {
            $customerId = auth()->user()->customer->id;
            $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])
                ->where('customer_id', $customerId)
                ->get();
        } elseif (auth()->user()->isHotel()) {
            $hotelId = auth()->user()->hotel->id;
            $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])
                ->where('hotel_id', $hotelId)
                ->get();
        }

        return response()->json($reservations);
    }

    /**
     * Display the specified reservation.
     *
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function show(ReservationController $reservation)
    {
        return response()->json($reservation->load(['customer.user', 'roomType', 'hotel']));
    }

    /**
     * Store a newly created reservation.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());
        return response()->json($reservation, 201);
    }

    /**
     * Update the specified reservation.
     *
     * @param Request $request
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated());
        return response()->json($reservation);
    }

    /**
     * Remove the specified reservation.
     *
     * @param Reservation $reservation
     * @return JsonResponse
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }
}
