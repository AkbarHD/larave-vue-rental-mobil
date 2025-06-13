<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\NotificationService;

class RentalController extends Controller
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $rentals = Rental::with(['car', 'paymentMethod', 'payments'])->latest()->paginate(10);

        return Inertia::render('Admin/Rentals/Index', [
            'rentals' => $rentals,
        ]);
    }

    public function show(Rental $rental)
    {
        $addons = $rental->addons;
        $addonTotalFee = $addons->sum('pivot.total_price');
        $rentalFeeDetails = [
            'base_rental_fee' => $rental->base_rental_fee,
            'additional_fee' => $rental->additional_fee,
            'addon_total_fee' => $addonTotalFee,
            'late_fee' => $rental->late_fee,
            'total_fee' => $rental->total_fee,
        ];
        return Inertia::render('Web/Rentals/Show', [
            'rental' => $rental->load('car', 'payments', 'addons', 'user'),
            'rentalFeeDetails' => $rentalFeeDetails,
        ]);
    }

    public function approve($id)
    {
        $rental = Rental::findOrFail($id);
        $rental->status = 'approved';
        $car = $rental->car;
        $car->status = 'rented';

        $rentalPayments = $rental->payments;
        foreach ($rentalPayments as $payment) {
            $payment->payment_status = 'paid';
            $payment->paid_at = Carbon::now();
            $payment->save();
        }

        $rental->save();
        $car->save();

        $this->notificationService->sendApprovedNotification($rental);
        return back();
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $rental = Rental::findOrFail($id);
        $rental->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $rentalPayments = $rental->payments;
        foreach ($rentalPayments as $payment) {
            $payment->update([
                'payment_status' => 'failed',
                'paid_at' => null
            ]);
        }

        $this->notificationService->sendRejectedNotification($rental, $request->rejection_reason);
        return back();
    }

    public function confirmReturn(Request $request, $id)
    {
        $rental = Rental::findOrFail($id);
        $rentalEndDate = Carbon::parse($rental->rental_end_date);
        $returnDate = Carbon::parse($request->return_date);
        $isLateReturn = $returnDate->gt($rentalEndDate);

        $validationRules = [
            'return_date' => 'required|date',
            'car_status' => 'required|in:available,rented,maintenance',
        ];

        if ($isLateReturn) {
            $validationRules['late_fee_payment_proof'] = 'required|image|max:2048';
        } else {
            $validationRules['late_fee_payment_proof'] = 'nullable|image|max:2048';
        }

        $validatedData = $request->validate($validationRules);
        $car = $rental->car;

        if ($isLateReturn) {
            $lateDays = $rentalEndDate->diffInDays($returnDate);
            $lateFee = $lateDays * $car->penalty_rate_per_day;
            $rental->late_fee = $lateFee;
            $rental->total_fee += $lateFee;

            if ($request->hasFile('late_fee_payment_proof')) {
                $hashName = $request->file('late_fee_payment_proof')->hashName();
                $request->file('late_fee_payment_proof')->storeAs('late_fee_proofs', $hashName, 'public');
                $rental->late_fee_payment_proof = $hashName;
            }

            $this->notificationService->sendLateReturnNotification($rental, $lateFee);
        } else {
            $this->notificationService->sendReturnOnTimeNotification($rental);
        }

        // Proses pengembalian
        $rental->return_date = $validatedData['return_date'];
        $rental->is_returned = true;
        $rental->save();

        // Update status mobil
        $car->status = $validatedData['car_status'];
        $car->save();

        return back();
    }
}
