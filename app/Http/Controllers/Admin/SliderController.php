<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->paginate(5);

        return inertia('Admin/Sliders/Index', [
            'sliders' => $sliders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,webp|max:2048',
        ]);

        $image = $request->file('image');
        $image->storeAs('sliders', $image->hashName(), 'public');

        Slider::create([
            'image' => $image->hashName(),
        ]);

        return redirect()->route('admin.sliders.index');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        if ($slider->image) {
            Storage::disk('public')->delete('sliders/' . basename($slider->image));
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index');
    }
}
