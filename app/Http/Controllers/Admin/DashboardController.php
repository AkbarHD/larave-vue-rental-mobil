<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $totalCars = Car::count();
        $rentedCars = Car::where('status', 'rented')->count();

        $totalCustomers = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        $totalRentals = Rental::count();
        $totalRevenue = Rental::where('status', 'approved')->sum('total_fee');
        $totalLateFees = Rental::where('status', 'approved')->sum('late_fee');

        $pendingRentals = Rental::where('status', 'pending')->count();
        $approvedRentals = Rental::where('status', 'approved')->count();
        $rejectedRentals = Rental::where('status', 'rejected')->count();

        $popularCars = Rental::select('car_id', DB::raw('count(*) as rental_count'))
            ->groupBy('car_id')
            ->orderBy('rental_count', 'desc')
            ->limit(5)
            ->with('car:id,brand,model')
            ->get()
            ->map(function ($item) {
                return [
                    'car_name' => $item->car ? ($item->car->brand ?: $item->car->model ?: "Mobil #{$item->car->id}") : 'Tidak ada data',
                    'rental_count' => $item->rental_count
                ];
            });

        return inertia('Admin/Dashboard/Index', [
            'totalCars' => $totalCars,
            'rentedCars' => $rentedCars,
            'totalCustomers' => $totalCustomers,
            'rentalStats' => [
                'total_rentals' => $totalRentals,
                'total_revenue' => $totalRevenue,
                'total_late_fees' => $totalLateFees,
                'status_counts' => [
                    'pending' => $pendingRentals,
                    'approved' => $approvedRentals,
                    'rejected' => $rejectedRentals
                ],
                'popular_cars' => $popularCars
            ]
        ]);
    }
}
