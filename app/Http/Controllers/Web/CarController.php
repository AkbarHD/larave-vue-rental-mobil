<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Car;
use App\Models\PaymentMethod;
use App\Models\Rental;
use App\Models\RentalPayment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\TripayService;
use App\Services\NotificationService;
use Carbon\Carbon;

class CarController extends Controller
{

    protected $tripayService;
    protected $notificationService;

    public function __construct(TripayService $tripayService, NotificationService $notificationService)
    {
        $this->tripayService = $tripayService;
        $this->notificationService = $notificationService;
    }


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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'rental_start_date' => 'required|date|after_or_equal:today',
            'rental_end_date' => 'required|date|after:rental_start_date',
            'usage_type' => 'required|in:in_city,out_of_city',
            'payment_type' => 'required|in:manual,tripay',
            'addons' => 'nullable|exists:addons,id',
        ]);

        if ($request->payment_type === 'manual') {
            $request->validate([
                'payment_method_id' => 'required|exists:payment_methods,id',
                'payment_proof' => 'required|image|max:2048',
            ]);
        } else {
            $request->validate([
                'payment_method' => 'required|string',
            ]);
        }

        $car = Car::findOrFail($validatedData['car_id']);
        $startDate = Carbon::parse($validatedData['rental_start_date']);
        $endDate = Carbon::parse($validatedData['rental_end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $baseRentalFee = $car->daily_rate * $totalDays;
        $additionalFee = $validatedData['usage_type'] === 'out_of_city' ? $baseRentalFee * 0.2 : 0;

        $addonsTotalFee = 0;
        $addon = null;
        if (isset($validatedData['addons'])) {
            $addon = Addon::find($validatedData['addons']);
            if ($addon) {
                $addonsTotalFee += $addon->price * 1;
            }
        }

        $additionalFee += $addonsTotalFee;
        $totalFee = $baseRentalFee + $additionalFee;

        $rental = Rental::create([
            'user_id' => $request->user()->id,
            'car_id' => $validatedData['car_id'],
            'payment_method_id' =>  $request->payment_type === 'manual' ? $request->payment_method_id : null,
            'payment_type' => $request->payment_type,
            'payment_method' => $request->payment_type === 'tripay' ? $request->payment_method : null,
            'rental_start_date' => $validatedData['rental_start_date'],
            'rental_end_date' => $validatedData['rental_end_date'],
            'total_days' => $totalDays,
            'usage_type' => $validatedData['usage_type'],
            'base_rental_fee' => $baseRentalFee,
            'additional_fee' => $additionalFee,
            'total_fee' => $totalFee,
            'status' => 'pending',
        ]);

        if ($addon) {
            $rental->addons()->attach($addon->id, [
                'quantity' => 1,
                'total_price' => $addon->price,
            ]);
        }

        if ($request->payment_type === 'manual') {
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $paymentProofPath = $file->storeAs('payment_proofs', $file->hashName(), 'public');
                $paymentProofPath = $file->hashName();
            }

            RentalPayment::create([
                'rental_id' => $rental->id,
                'amount' => $totalFee,
                'payment_proof' => $paymentProofPath,
                'payment_type' => 'manual',
            ]);

            // Send notification after rental creation
            $this->notificationService->sendRentalNotification($rental, $startDate, $endDate);
            return redirect()->route('rentals.show', $rental);
        } else {
            $tripayResponse = $this->tripayService->createTransaction(
                $request,
                $rental,
                $car,
                $addon,
                $totalFee,
                $baseRentalFee,
                $additionalFee
            );

            if (!$tripayResponse['success']) {
                $rental->delete();
                return redirect()->back()->withErrors(['tripay_error' => $tripayResponse['message']]);
            }
            RentalPayment::create([
                'rental_id' => $rental->id,
                'amount' => $totalFee,
                'payment_type' => 'tripay',
                'payment_reference' => $tripayResponse['data']['reference'],
                'payment_url' => $tripayResponse['data']['checkout_url'],
                'expired_at' => Carbon::createFromTimestamp($tripayResponse['data']['expired_time']),
            ]);

            $rental->update([
                'payment_reference' => $tripayResponse['data']['reference'],
                'status' => 'pending',
            ]);
            return redirect()->away($tripayResponse['data']['checkout_url']);
        }
    }
}
