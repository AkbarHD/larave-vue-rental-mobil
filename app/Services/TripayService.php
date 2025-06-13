<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TripayService
{
    protected $merchantCode;
    protected $privateKey;
    protected $apiKey;
    protected $isProduction;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('services.tripay.merchant_code');
        $this->privateKey = config('services.tripay.private_key');
        $this->apiKey = config('services.tripay.api_key');
        $this->isProduction = config('services.tripay.production', false);
        $this->baseUrl = config('services.tripay.base_url');
    }

    /**
     * Mendapatkan daftar channel pembayaran dari Tripay
     */
    public function getPaymentChannels()
    {
        $url = $this->baseUrl . '/merchant/payment-channel';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengambil data channel pembayaran: ' . $response->status()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Membuat transaksi pembayaran melalui Tripay
     */
    public function createTransaction(Request $request, Rental $rental, Car $car, $addon = null, $totalFee = 0, $baseRentalFee = 0, $additionalFee = 0)
    {
        try {
            $merchantRef = 'RENTAL-' . $rental->id . '-' . time();

            $user = auth()->user();

            $url = $this->isProduction ?
                'https://tripay.co.id/api/transaction/create' :
                $this->baseUrl . '/transaction/create';

            $tripayData = $this->prepareTransactionData(
                $request,
                $merchantRef,
                $user,
                $car,
                $totalFee,
                $baseRentalFee,
                $rental,
                $addon,
                $additionalFee
            );

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($url, $tripayData);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['success']) && $responseData['success']) {
                    return [
                        'success' => true,
                        'data' => $responseData['data'],
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $responseData['message'] ?? 'Gagal membuat transaksi',
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal menghubungi server Tripay: ' . $response->status(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Prepare transaction data for API request
     */
    private function prepareTransactionData(
        Request $request,
        string $merchantRef,
        $user,
        Car $car,
        float $totalFee,
        float $baseRentalFee,
        Rental $rental,
        $addon,
        float $additionalFee
    ) {
        $tripayData = [
            'method' => $request->payment_method,
            'merchant_ref' => $merchantRef,
            'amount' => $totalFee,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->whatsapp_number,
            'order_items' => [
                [
                    'name' => 'Rental Mobil ' . $car->brand . ' ' . $car->model,
                    'price' => $baseRentalFee,
                    'quantity' => 1,
                    'product_url' => route('rentals.show', $car->id),
                    'image_url' => asset($car->image),
                ],
            ],
            'return_url' => route('rentals.show', $rental),
            'callback_url' => route('tripay.callback'),
            'expired_time' => (time() + (24 * 60 * 60)),
        ];

        $signature = hash_hmac('sha256', $this->merchantCode . $merchantRef . $totalFee, $this->privateKey);
        $tripayData['signature'] = $signature;

        if ($addon) {
            $tripayData['order_items'][] = [
                'name' => $addon->name,
                'price' => $addon->price,
                'quantity' => 1,
            ];
        }

        if ($request->usage_type === 'out_of_city' && ($additionalFee - ($addon ? $addon->price : 0)) > 0) {
            $outCityFee = $additionalFee - ($addon ? $addon->price : 0);
            $tripayData['order_items'][] = [
                'name' => 'Biaya Luar Kota (20%)',
                'price' => $outCityFee,
                'quantity' => 1,
            ];
        }

        return $tripayData;
    }
}
