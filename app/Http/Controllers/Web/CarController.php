<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Car;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarController extends Controller
{

    public function index()
    {
        $cars = Car::latest()->paginate(3);

        return Inertia::render('Web/Cars/Index', [
            'cars' => $cars,
        ]);
    }

    public function show($slug)
    {
        $car = Car::where('slug', $slug)->firstOrFail();
        $paymentMethods = PaymentMethod::all();
        $addons = Addon::all();

        return Inertia::render('Web/Cars/Show', [
            'car' => $car,
            'paymentMethods' => $paymentMethods,
            'addons' => $addons,
        ]);
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 1);
        $cars = Car::latest()->paginate(2, ['*'], 'page', $page);

        return response()->json([
            'cars' => $cars
        ]);
    }
}
