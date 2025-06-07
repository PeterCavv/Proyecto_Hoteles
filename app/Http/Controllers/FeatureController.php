<?php

namespace App\Http\Controllers;

use App\Http\Requests\Feature\FeatureRequest;
use App\Models\Feature;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FeatureController extends Controller
{

    /**
     * Displays a list of all features.
     *
     * @return Response The rendered Inertia component with all feature data.
     */
    public function index()
    {
        return Inertia::render('Admin/FeaturesIndex')
            ->with([
                'features' => Feature::all()
            ]);
    }

    /**
     * Displays the specified feature.
     *
     * @param Feature $feature The feature instance to be displayed.
     * @return Response The rendered Inertia component with feature data.
     */
    public function show(Feature $feature)
    {
        return Inertia::render('Admin/FeatureShow')
            ->with([
                'feature' => $feature,
            ]);
    }

    /**
     * Updates the specified feature with validated data.
     *
     * @param FeatureRequest $request The request containing validated feature data.
     * @param Feature $feature The feature instance to be updated.
     * @return RedirectResponse A redirect response to the feature's show route.
     */
    public function update(FeatureRequest $request, Feature $feature)
    {
        $validated = $request->validated();

        $feature->update($validated);

        return redirect()->route('feature.show', $feature);
    }

    /**
     * Handles the storage of a new feature.
     *
     * @param FeatureRequest $request The incoming request containing the feature data.
     * @return RedirectResponse Redirects to the feature index route with all features.
     */
    public function store(FeatureRequest $request)
    {
        $validated = $request->validated();

        Feature::create($validated);

        return redirect()->route('feature.index');
    }

    /**
     * Deletes the specified feature from the database.
     *
     * @param Feature $feature The feature instance to be deleted.
     * @return RedirectResponse Redirects to the feature index route with all features.
     */
    public function destroy(Feature $feature)
    {
        $feature->delete();

        return redirect()->route('feature.index');
    }
}
