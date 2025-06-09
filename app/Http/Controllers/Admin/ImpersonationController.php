<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Start impersonating a user.
     *
     * Stores the current authenticated user's ID in the session as 'impersonator_id',
     * then logs in as the given user.
     *
     * @param User $user The user to impersonate.
     * @return RedirectResponse Redirects to the 'welcome' route after impersonation starts.
     */
    public function start(User $user): RedirectResponse
    {
        Session::put('impersonator_id', Auth::id());

        Auth::login($user);

        return redirect()->route('welcome');
    }

    /**
     * Stop impersonating and revert to the original user.
     *
     * Retrieves the original user ID from the session and logs back in as that user.
     * If no original user ID is found, simply redirects to the 'users.index' route.
     *
     * @return RedirectResponse Redirects to the 'users.index' route after impersonation stops.
     */
    public function stop(): RedirectResponse
    {
        $originalId = Session::pull('impersonator_id');
        if ($originalId) {
            Auth::loginUsingId($originalId);
        }

        return redirect()->route('users.index');
    }

}
