<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isAdmin = Auth::user()->hasRole('admin');

        return Inertia::render('Web/Profile/Index', [
            'user' => $user,
            'isAdmin' => $isAdmin
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        $user->update($request->only(['name', 'address', 'whatsapp_number']));

        return redirect()->back();
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('image')) {

            if ($user->image && Storage::exists('public/users/' . $user->image)) {
                Storage::delete('public/users/' . $user->image);
            }

            $image = $request->file('image');
            $imageName = $image->hashName();
            $image->storeAs('users', $imageName, 'public');

            $user->image = $imageName;
            $user->save();
        }

        return redirect()->back();
    }
}
