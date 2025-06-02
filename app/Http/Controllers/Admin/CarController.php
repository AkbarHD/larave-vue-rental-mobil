<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->paginate(10);

        return inertia('Admin/Cars/Index', [
            'cars' => $cars,
        ]);
    }

    public function edit($id)
    {
        $car = Car::findOrFail($id);
        $categories = Category::all();

        return inertia('Admin/Cars/Edit', [
            'car' => $car,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $validatedData = $request->validate([
            'brand'             => 'required|string|max:255',
            'model'             => 'required|string|max:255',
            'license_plate'     => 'required|string|unique:cars,license_plate,' . $car->id . '|max:255',
            'transmission'      => 'required|in:manual,automatic',
            'year'              => 'required|integer',
            'daily_rate'        => 'required|numeric',
            'penalty_rate_per_day' => 'required|numeric|min:0',
            'passenger_capacity' => 'required|integer',
            'fuel_type'         => 'required|in:gasoline,diesel,electric',
            'status'            => 'nullable|in:available,rented,maintenance',
            'description'       => 'nullable|string',
            'category_id'         => 'required|exists:categories,id',
            'image'             => 'nullable|image|mimes:png,jpg,webp|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['brand']);


        if ($request->hasFile('image')) {
            if ($car->image) {
                Storage::disk('public')->delete('cars/' . $car->image);
            }

            $image = $request->file('image');
            $validatedData['image'] = $image->hashName();
            $image->storeAs('cars', $image->hashName(), 'public');
        }

        $car->update($validatedData);

        return redirect()->route('admin.cars.index');
    }

    public function create()
    {
        $categories = Category::all();

        return inertia('Admin/Cars/Create', [
            'categories' => $categories,
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'brand'              => 'required|string|max:255',
            'model'              => 'required|string|max:255',
            'license_plate'      => 'required|string|max:255',
            'transmission'       => 'required|string|max:255',
            'year'               => 'required|integer|min:1900|max:' . date('Y'),
            'daily_rate'         => 'required|numeric|min:0',
            'penalty_rate_per_day' => 'required|numeric|min:0',
            'passenger_capacity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'fuel_type'          => 'required|string|max:255',
            'description'        => 'nullable|string',
            'image'              => 'required|image|mimes:png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validatedData['image'] = $image->storeAs('cars', $image->hashName(), 'public');
            $validatedData['image'] = $image->hashName();
        }

        $validatedData['slug'] = Str::slug($validatedData['brand']);

        Car::create($validatedData);

        return redirect()->route('admin.cars.index');
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->image) {
            Storage::disk('local')->delete('public/cars/' . basename($car->image));
        }

        $car->delete();

        return redirect()->route('admin.cars.index');
    }
}
