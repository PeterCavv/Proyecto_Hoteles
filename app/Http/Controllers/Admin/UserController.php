<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Response;

class UserController extends Controller
{
    use AuthorizesRequests;

    /**
     * Displays a list of all users.
     *
     * @return Response The rendered Inertia component with all feature data.
     */
    public function index()
    {
        $this->authorize('index', User::class);

        $users = User::all()->load('customer');

        return inertia('Admin/UserIndex', [
            'users' => $users,
            'role' => auth()->user() ? auth()->user()->getRoleNames() : [],
        ]);
    }
}
