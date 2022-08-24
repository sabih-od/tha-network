<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    use UserData;
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request): array
    {
        $share_data = [
            'auth' => auth()->check() ? auth()->user()->only('id', 'username', 'email') : null,
            'auth_profile_image' => auth()->check() && auth()->user()->getFirstMediaUrl('profile_image') ? auth()->user()->getFirstMediaUrl('profile_image') : asset('images/small-character.jpg'),
            'flash' => [
                'success' => function () use ($request) {
                    return $request->session()->get('success');
                },
                'error' => function () use ($request) {
                    return $request->session()->get('error');
                }
            ],
            'peoples' => Inertia::lazy(function () use ($request) {
                return $this->getPeoplesData($request);
            }),
            'new_members' => Inertia::lazy(function () use ($request) {
                return $this->getNewMembersData($request);
            }),
            'friends' => Inertia::lazy(function () use ($request) {
                return $this->getFriendsData($request);
            })
        ];

        if ($request->session()->has('v_data')) {
            $share_data['v_data'] = $request->session()->get('v_data');
        }

        return array_merge(parent::share($request), $share_data);
    }
}
