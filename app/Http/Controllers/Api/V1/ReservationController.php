<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CreateReservationEvent;
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
     * @authenticated
     *
     * The result depends on the role of the authenticated user:
     * - Admin: all reservations with related customer, roomType, and hotel.
     * - Customer: reservations only for the authenticated customer.
     * - Hotel: reservations only for the authenticated hotel.
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "customer": { "id": 2, "user": { "name": "John Doe", "email": "john@example.com" } },
     *     "roomType": { "id": 3, "name": "Suite" },
     *     "hotel": { "id": 1, "name": "Hotel California" },
     *     "check_in": "2025-06-15",
     *     "check_out": "2025-06-20"
     *   }
     * ]
     *
     * @return JsonResponse JSON response with reservations.
     */
    public function index()
    {
        try {
            if (auth()->user()->isAdmin()) {
                $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])->get();
            } elseif (auth()->user()->isCustomer() && auth()->user()->customer) {
                $customerId = auth()->user()->customer->id;
                $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])
                    ->where('customer_id', $customerId)
                    ->get();
            } elseif (auth()->user()->isHotel() && auth()->user()->hotel) {
                $hotelId = auth()->user()->hotel->id;
                $reservations = Reservation::with(['customer.user', 'roomType', 'hotel'])
                    ->where('hotel_id', $hotelId)
                    ->get();
            } else {
                $reservations = collect();
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json($reservations);
    }

    /**
     * Display the specified reservation.
     *
     * @authenticated
     *
     * @urlParam reservation int required The ID of the reservation. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "customer": { "id": 2, "user": { "name": "John Doe", "email": "john@example.com" } },
     *   "roomType": { "id": 3, "name": "Suite" },
     *   "hotel": { "id": 1, "name": "Hotel California" },
     *   "check_in": "2025-06-15",
     *   "check_out": "2025-06-20"
     * }
     *
     * @param Reservation $reservation The reservation instance to show.
     * @return JsonResponse JSON response with reservation and its relations.
     */
    public function show(Reservation $reservation)
    {
        return response()->json($reservation->load(['customer.user', 'roomType', 'hotel']));
    }

    /**
     * Store a newly created reservation.
     *
     * @authenticated
     *
     * @bodyParam customer_id int required The ID of the customer making the reservation. Example: 2
     * @bodyParam room_type_id int required The ID of the room type. Example: 3
     * @bodyParam hotel_id int required The ID of the hotel. Example: 1
     * @bodyParam check_in date required The check-in date. Example: 2025-06-15
     * @bodyParam check_out date required The check-out date. Example: 2025-06-20
     *
     * @response 201 {
     *   "id": 5,
     *   "customer_id": 2,
     *   "room_type_id": 3,
     *   "hotel_id": 1,
     *   "check_in": "2025-06-15",
     *   "check_out": "2025-06-20"
     * }
     *
     * @param ReservationRequest $request Validated reservation request.
     * @return JsonResponse JSON response with the created reservation and HTTP status 201.
     */
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::create($request->validated());

        event(new CreateReservationEvent($reservation));

        return response()->json($reservation, 201);
    }

    /**
     * Update the specified reservation.
     *
     * @urlParam reservation int required The ID of the reservation to update. Example: 5
     * @bodyParam customer_id int The ID of the customer making the reservation. Example: 2
     * @bodyParam room_type_id int The ID of the room type. Example: 3
     * @bodyParam hotel_id int The ID of the hotel. Example: 1
     * @bodyParam check_in date The check-in date. Example: 2025-06-15
     * @bodyParam check_out date The check-out date. Example: 2025-06-20
     *
     * @response 200 {
     *   "id": 5,
     *   "customer_id": 2,
     *   "room_type_id": 3,
     *   "hotel_id": 1,
     *   "check_in": "2025-06-15",
     *   "check_out": "2025-06-20"
     * }
     *
     * @param ReservationRequest $request Validated reservation request.
     * @param Reservation $reservation The reservation to update.
     * @return JsonResponse JSON response with the updated reservation.
     */
    public function update(ReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->validated());
        return response()->json($reservation);
    }

    /**
     * Remove the specified reservation.
     *
     * @urlParam reservation int required The ID of the reservation to delete. Example: 5
     *
     * @response 204 {}
     *
     * @param Reservation $reservation The reservation to delete.
     * @return JsonResponse JSON response with no content and HTTP status 204.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(null, 204);
    }
}

