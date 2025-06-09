<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityRequest;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{

    use AuthorizesRequests;

    /**
     * Display a listing of all cities.
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Paris",
     *     "country": "France"
     *   },
     *   {
     *     "id": 2,
     *     "name": "New York",
     *     "country": "USA"
     *   }
     * ]
     *
     * @return JsonResponse JSON response with the list of all cities.
     */
    public function index()
    {
        return response()->json(City::all());
    }

    /**
     * Store a newly created city in storage.
     *
     * @bodyParam name string required The name of the city. Example: Paris
     * @bodyParam country string required The country of the city. Example: France
     *
     * @response 201 {
     *   "id": 1,
     *   "name": "Paris",
     *   "country": "France"
     * }
     *
     * @throws AuthorizationException If the user is not authorized to create a city.
     *
     * @param CityRequest $request Validated city request.
     * @return JsonResponse JSON response with the created city and HTTP status 201.
     */
    public function store(CityRequest $request)
    {
        $this->authorize('create', City::class);

        $validated = $request->validated();

        $city = City::create($validated);

        return response()->json($city, 201);
    }

    /**
     * Update the specified city in storage.
     *
     * @urlParam city int required The ID of the city to update. Example: 1
     * @bodyParam name string The name of the city. Example: Paris
     * @bodyParam country string The country of the city. Example: France
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Paris",
     *   "country": "France"
     * }
     *
     * @throws AuthorizationException If the user is not authorized to update the city.
     *
     * @param CityRequest $request Validated city request.
     * @param City $city The city to update.
     * @return JsonResponse JSON response with the updated city.
     */
    public function update(CityRequest $request, City $city)
    {
        $this->authorize('update', $city);

        $validated = $request->validated();

        $city->update($validated);

        return response()->json($city);
    }

    /**
     * Remove the specified city from storage.
     *
     * @urlParam city int required The ID of the city to delete. Example: 1
     *
     * @response 204 {}
     *
     * @throws AuthorizationException If the user is not authorized to delete the city.
     *
     * @param City $city The city to delete.
     * @return JsonResponse JSON response with no content and HTTP status 204.
     */
    public function destroy(City $city)
    {
        $this->authorize('destroy', $city);

        $city->delete();

        return response()->json(null, 204);
    }


}
