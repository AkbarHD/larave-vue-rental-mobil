<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RentalReportController extends Controller
{
    public function index()
    {
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        return Inertia::render('Admin/Reports/Index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'reportData' => $this->generateReport($startDate, $endDate),
        ]);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        return response()->json([
            'reportData' => $this->generateReport($startDate, $endDate),
        ]);
    }

    private function generateReport($startDate, $endDate)
    {
        $rentals = Rental::with(['car', 'user', 'paymentMethod'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('rental_start_date', [$startDate, $endDate])
                    ->orWhereBetween('rental_end_date', [$startDate, $endDate]);
            })
            ->latest()
            ->paginate(10);

        $transformedData = $rentals->through(function ($rental) {
            return [
                'id' => $rental->id,
                'user_name' => $rental->user->name,
                'car_name' => $rental->car->brand,
                'rental_start_date' => $rental->rental_start_date,
                'rental_end_date' => $rental->rental_end_date,
                'total_days' => $rental->total_days,
                'usage_type' => $rental->usage_type,
                'base_rental_fee' => $rental->base_rental_fee,
                'additional_fee' => $rental->additional_fee,
                'late_fee' => $rental->late_fee,
                'total_fee' => $rental->total_fee,
                'payment_type' => $rental->payment_type,
                'status' => $rental->status,
                'is_returned' => $rental->is_returned,
                'created_at' => $rental->created_at->format('d M Y H:i'),
            ];
        });

        return $transformedData;
    }
}
