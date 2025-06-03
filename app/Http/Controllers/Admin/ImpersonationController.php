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
     * @param User $user
     * @return RedirectResponse
     */
    public function start(User $user): RedirectResponse
    {
        Session::put('impersonator_id', Auth::id());

        Auth::login($user);

        return redirect()->route('welcome');
    }

    /**
     * Stop impersonating the user and return to the original user.
     *
     * @return RedirectResponse
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
