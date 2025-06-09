<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\HotelRequest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    use AuthorizesRequests;

    /**
     * Updates the specified hotel's information.
     *
     * @param HotelRequest $request The request object containing the validated data for updating the hotel.
     * @param Hotel $hotel The hotel instance to be updated.
     * @return RedirectResponse Redirects to the hotel's show route after a successful update.
     */
    public function update(HotelRequest $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        $validated = $request->validated();

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel);
    }

    /**
     * Deletes a specified hotel and its associated user.
     *
     * @param Hotel $hotel The hotel instance to be deleted
     */
    public function delete(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);
        $user = User::findOrFail($hotel->user_id);

        $hotel->delete();

        $user->syncRoles();
        $user->delete();

        return redirect()->route('welcome');
    }

    /**
     * Associates a list of features with the specified hotel.
     *
     * @param Request $request The request object containing the validated feature data.
     * @param Hotel $hotel The hotel model to which the features will be linked.
     * @return RedirectResponse Redirects back to the previous page with a success message after adding features.
     */
    public function addFeatures(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $request->validate([
            'features' => 'required|array',
            'features.*' => 'exists:features,id',
        ]);

        $hotel->features()->sync($data['features']);

        return redirect()->route('hotels.show', $hotel);
    }

}
