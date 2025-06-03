<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['rental.car', 'user'])->paginate(10);
        return Inertia::render('Admin/Reviews/Index', [
            'reviews' => $reviews,
        ]);
    }
}
