<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SiteController extends Controller
{
    public function index()
    {
        $siteSetting = SiteSetting::first() ?? new SiteSetting();
        return Inertia::render('Admin/SiteSettings/Index', [
            'siteSetting' => $siteSetting,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
            'location_embed_links' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $siteSetting = SiteSetting::first();
        if (!$siteSetting) {
            $siteSetting = new SiteSetting();
        }

        $siteSetting->site_name = $request->site_name;
        $siteSetting->facebook_url = $request->facebook_url;
        $siteSetting->twitter_url = $request->twitter_url;
        $siteSetting->instagram_url = $request->instagram_url;
        $siteSetting->whatsapp_url = $request->whatsapp_url;
        $siteSetting->description = $request->description;

        if ($request->has('location_embed_links')) {
            $siteSetting->location_embed_links = json_encode($request->location_embed_links);
        }

        if ($request->hasFile('logo')) {

            if ($siteSetting->getRawOriginal('logo') && Storage::disk('public')->exists('logos/' . $siteSetting->getRawOriginal('logo'))) {
                Storage::disk('public')->delete('logos/' . $siteSetting->getRawOriginal('logo'));
            }

            $logo = $request->file('logo');
            $logoName = $logo->hashName();
            $logo->storeAs('logos', $logoName, 'public');
            $siteSetting->logo = $logoName;
        }

        $siteSetting->save();

        return redirect()->back();
    }
}
