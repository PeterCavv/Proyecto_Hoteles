<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function show(User $user)
    {
        return inertia('Profile/UserProfile', [
            'user' => $user->load('roles', 'customer'),
            'authUserId' => auth()->id(),
            'csrfToken' => csrf_token(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        if($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $user->update($request->validated());

        return redirect()->route('profile.show', $user);
    }
}
