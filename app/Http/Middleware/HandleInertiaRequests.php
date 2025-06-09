<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'is_admin' => $request->user()?->isAdmin(),
                'is_customer' => $request->user()?->isCustomer(),
                'is_employee' => $request->user()?->isHotel(),
                'impersonating' => Session::has('impersonator_id'),
            ],

            'locale' => App::getLocale(),

            'translations' => function () {
                return [
                    'messages' => trans('messages'),
                    'validation' => trans('validation'),
                ];
            },
        ];
    }
}
