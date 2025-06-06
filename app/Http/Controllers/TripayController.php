<?php

namespace App\Http\Controllers;

use App\Services\TripayService;

class TripayController extends Controller
{

    protected $tripayService;

    public function __construct(TripayService $tripayService)
    {
        $this->tripayService = $tripayService;
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
}
