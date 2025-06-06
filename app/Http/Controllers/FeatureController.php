<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feature\FeatureRequest;
use App\Models\Feature;
use Inertia\Inertia;

class FeatureController extends Controller
{

    /**
     * Return all features
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Admin/FeaturesIndex')
            ->with([
                'features' => Feature::all()
            ]);
    }

    public function show(Feature $feature)
    {
        return Inertia::render('Admin/FeatureShow')
            ->with([
                'feature' => $feature,
            ]);
    }

    public function update(FeatureRequest $request, Feature $feature)
    {
        $validated = $request->validated();

        $feature->update($validated);

        return redirect()->route('feature.show', $feature);
    }

    public function store(FeatureRequest $request)
    {
        $validated = $request->validated();

        Feature::create($validated);

        return redirect()->route('feature.index', Feature::all());
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('feature.index', Feature::all());
    }
}
