<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attraction\AttractionRequest;
use App\Models\Attraction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    /**
     * Display a listing of attractions with optional filters.
     *
     * You can filter attractions by city ID, name, and type.
     *
     * @queryParam city int Optional. Filter attractions by city ID. Example: 10
     * @queryParam name string Optional. Filter attractions by name. Example: Eiffel Tower
     * @queryParam type string Optional. Filter attractions by type. Example: pay
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Eiffel Tower",
     *     "type": "pay",
     *     "city": {
     *       "id": 10,
     *       "name": "Paris"
     *     }
     *   }
     * ]
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filters = $request->only(['city', 'name', 'type']);

        $attractions = Attraction::with('city')
            ->filter($filters)
            ->get();

        return response()->json($attractions);
    }

    /**
     * Display the specified attraction with its related city.
     *
     * @urlParam id int required The ID of the attraction. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Eiffel Tower",
     *   "type": "Monument",
     *   "city": {
     *     "id": 10,
     *     "name": "Paris"
     *   }
     * }
     *
     * @response 404 {
     *   "message": "No query results for model [App\\Models\\Attraction] 999"
     * }
     *
     * @param int|string $id
     * @return Model
     *
     * @throws ModelNotFoundException
     */
    public function show(int|string $id)
    {
        $attraction = Attraction::findOrFail($id);

        return $attraction->load('city');
    }

    /**
     * Store a newly created attraction in storage.
     *
     * @bodyParam name string required The name of the attraction. Example: Eiffel Tower
     * @bodyParam type string required The type of the attraction (only pay or free). Example: pay
     * @bodyParam city_id int required The ID of the city where the attraction is located. Example: 10
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "Eiffel Tower",
     *   "type": "Monument",
     *   "city_id": 10
     * }
     *
     * @param AttractionRequest $request
     * @return JsonResponse
     */
    public function store(AttractionRequest $request)
    {
        $validated = $request->validated();

        $attraction = Attraction::create($validated);

        return response()->json($attraction, 201);
    }

    /**
     * Update the specified attraction in storage.
     *
     * @urlParam id int required The ID of the attraction to update. Example: 1
     *
     * @bodyParam name string The name of the attraction. Example: Louvre Museum
     * @bodyParam type string The type of the attraction (only pay or free). Example: pay
     * @bodyParam city_id int The ID of the city where the attraction is located. Example: 10
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Louvre Museum",
     *   "type": "Museum",
     *   "city_id": 10
     * }
     *
     * @param AttractionRequest $request
     * @param Attraction $attraction
     * @return JsonResponse
     */
    public function update(AttractionRequest $request, Attraction $attraction)
    {
        $validated = $request->validated();

        $attraction->update($validated);

        return response()->json($attraction);
    }

    /**
     * Remove the specified attraction from storage.
     *
     * @urlParam id int required The ID of the attraction to delete. Example: 1
     *
     * @response 204 {}
     *
     * @param Attraction $attraction
     * @return JsonResponse
     */
    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        return response()->json(null, 204);
    }
}
