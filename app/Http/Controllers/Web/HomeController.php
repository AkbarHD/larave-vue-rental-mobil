<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Category;
use App\Models\Review;
use App\Models\Slider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        $sliders = Slider::all();
        $categories = Category::all();

        $reviews = Review::with([
            'user',
            'rental.car'
        ])
            ->latest()
            ->get();

        return Inertia::render('Web/Home/Index', [
            'cars' => $cars,
            'sliders' => $sliders,
            'reviews' => $reviews,
            'categories' => $categories,
        ]);
    }
}
