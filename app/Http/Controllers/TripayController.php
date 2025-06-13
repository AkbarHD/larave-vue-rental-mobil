<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\RentalPayment;
use Illuminate\Http\Request;
use App\Services\TripayService;
use App\Services\NotificationService;

class TripayController extends Controller
{

    protected $tripayService;
    protected $notificationService;

    public function __construct(TripayService $tripayService, NotificationService $notificationService)
    {
        $this->tripayService = $tripayService;
        $this->notificationService = $notificationService;
    }

    /**
     * Mendapatkan daftar channel pembayaran dari Tripay
     */
    public function getPaymentChannels()
    {
        $response = $this->tripayService->getPaymentChannels();

        if ($response['success']) {
            return response()->json($response['data']);
        }

        return response()->json(['error' => $response['message']], 500);
    }

    /**
     * Process payment callback from Tripay
     */
    /**
     * Process payment callback from Tripay
     */
    public function handleCallback(Request $request)
    {
        $callbackSignature = $request->header('X-Callback-Signature');
        $json = $request->getContent();

        $privateKey = env('TRIPAY_PRIVATE_KEY');
        $signature = hash_hmac('sha256', $json, $privateKey);

        if ($callbackSignature !== $signature) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature',
            ], 400);
        }

        $data = json_decode($json);

        $rental = Rental::where('payment_reference', $data->merchant_ref)
            ->orWhere('payment_reference', $data->reference)
            ->first();

        if (!$rental) {
            return response()->json([
                'success' => false,
                'message' => 'Rental not found',
            ], 404);
        }

        $payment = RentalPayment::where('rental_id', $rental->id)->first();

        if ($data->status == 'PAID') {
            $rental->update([
                'status' => 'approved',
                'payment_method' => $data->payment_method_code,
                'payment_reference' => $data->reference
            ]);

            $car = Car::findOrFail($rental->car_id);
            $car->update([
                'status' => 'rented'
            ]);

            if ($payment) {
                $payment->update([
                    'paid_at' => now(),
                    'payment_reference' => $data->reference,
                    'payment_status' => 'paid'
                ]);
            }

            // Kirim notifikasi pembayaran berhasil
            $this->notificationService->sendApprovedNotification($rental, $data->reference);
        } elseif ($data->status == 'EXPIRED') {
            $rental->update(['status' => 'rejected']);

            if ($payment) {
                $payment->update([
                    'payment_status' => 'expired',
                    'expired_at' => now()
                ]);
            }
        } elseif ($data->status == 'FAILED') {
            $rental->update(['status' => 'rejected']);

            if ($payment) {
                $payment->update([
                    'payment_status' => 'failed'
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
