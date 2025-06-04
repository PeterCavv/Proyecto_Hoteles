<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\City\CityRequest;
use App\Models\City;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CityController extends Controller
{

    use AuthorizesRequests;

    public function index()
    {
        return City::all();
    }

    public function store(CityRequest $request)
    {
        $this->authorize('create', City::class);

        $validated = $request->validated();

        $city = City::create($validated);

        return response()->json($city, 201);
    }

    public function update(CityRequest $request, City $city)
    {
        $this->authorize('update', $city);

        $validated = $request->validated();

        $city->update($validated);

        return response()->json($city);
    }

    public function destroy(City $city)
    {
        $this->authorize('store', $city);

        $city->delete();

        return response()->json(null, 204);
    }
}
