<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RentalController extends Controller
{
    public function index()
    {
        $isAdmin = Auth::user()->hasRole('admin');

        $rentals = Rental::with(['car', 'paymentMethod'])
            ->when(!$isAdmin, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10);
        return Inertia::render('Web/Rentals/Index', [
            'rentals' => $rentals,
        ]);
    }
}
