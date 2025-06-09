<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the specified user's profile page with related roles and customer data.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return inertia('Profile/UserProfile', [
            'user' => $user->load('roles', 'customer', 'reviews'),
            'authUserId' => auth()->id(),
            'csrfToken' => csrf_token(),
        ]);
    }

    /**
     * Update the specified user's profile information.
     *
     * Checks authorization before updating.
     * Resets email verification timestamp if the email is changed.
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $user->update($request->validated());

        return redirect()->route('profile.show', $user);
    }

    public function reviews(User $user)
    {
        return inertia('Profile/UserReviews', [
            'reviews' => $user->reviews->load('hotel'),
            'user' => $user
        ]);
    }
}
