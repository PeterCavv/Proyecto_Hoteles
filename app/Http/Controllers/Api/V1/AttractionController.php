<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attraction\AttractionRequest;
use App\Models\Attraction;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $attractions = Attraction::with('city')
        ->filter($request->only(['city', 'name']))
        ->get();

        return response()->json($attractions);
    }

    public function show(Attraction $attraction)
    {
        return $attraction->load('city');
    }

    public function store(AttractionRequest $request)
    {
        $validated = $request->validated();

        $attraction = Attraction::create($validated);

        return response()->json($attraction, 201);
    }

    public function update(AttractionRequest $request, Attraction $attraction)
    {
        $validated = $request->validated();

        $attraction->update($validated);

        return response()->json($attraction);
    }

    public function destroy(Attraction $attraction)
    {
        $attraction->delete();

        return response()->json(null, 204);
    }
}
