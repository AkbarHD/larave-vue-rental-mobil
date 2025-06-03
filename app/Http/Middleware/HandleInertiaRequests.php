<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $siteSetting = SiteSetting::first();

        return array_merge(parent::share($request), [
           'siteSetting' => [
                'site_name' => $siteSetting->site_name ?? '',
                'logo' => $siteSetting->logo ?? null,
                'facebook_url' => $siteSetting->facebook_url ?? null,
                'twitter_url' => $siteSetting->twitter_url ?? null,
                'instagram_url' => $siteSetting->instagram_url ?? null,
                'whatsapp_url' => $siteSetting->whatsapp_url ?? null,
                'location_embed_links' => $siteSetting->location_embed_links ?? null,
                'description' => $siteSetting->description ?? null,
            ],

            //user authenticated
            'auth'  => [
                'user'          => \Illuminate\Support\Facades\Auth::user() ? \Illuminate\Support\Facades\Auth::user() : null,
                'isAdmin' => Auth::check() && Auth::user()->hasRole('admin'),
            ],
        ]);
    }
}
