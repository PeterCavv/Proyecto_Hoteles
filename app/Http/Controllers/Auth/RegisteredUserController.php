<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Events\CreatedUserEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $deletedUser = User::withTrashed()->where('email', $request->email)->first();

        if ($deletedUser) {
            $deletedUser->restore();
            $deletedUser->update([
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ]);

            $deletedUser->assignRole(RoleEnum::CUSTOMER->value);

            if ($deletedUser->customer()->withTrashed()->exists()) {
                $deletedUser->customer()->withTrashed()->first()->restore();
                $deletedUser->customer()->update([
                    'dni' => $request->dni,
                ]);
            } else {
                $deletedUser->customer()->create([
                    'dni' => $request->dni,
                ]);
            }

            event(new Registered($deletedUser));
            event(new CreatedUserEvent($deletedUser));

            Auth::login($deletedUser);
            return redirect(route('common.index', absolute: false));
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dni' => ['nullable', 'string', 'max:9',
                Rule::unique('customers')->whereNull('deleted_at')]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(RoleEnum::CUSTOMER->value);
        $user->customer()->create([
            'dni' => $request->dni,
        ]);
        event(new Registered($user));
        event(new CreatedUserEvent($user));

        Auth::login($user);

        return redirect(route('common.index', absolute: false));
    }
}
