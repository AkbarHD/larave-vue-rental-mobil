<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappNotificationService
{
    /**
     * Kirim pesan WhatsApp
     *
     * @param \App\Models\Rental $rental
     * @param string $message
     * @return void
     */
    public function sendNotification($rental, $message)
    {
        $userPhoneNumber = $rental->user->whatsapp_number;
        $apiToken = config('services.fonnte.api_token');
        Http::withHeaders([
            'Authorization' => $apiToken,
        ])->post('https://api.fonnte.com/send', [
            'target' => $userPhoneNumber,
            'message' => $message,
            'countryCode' => '62',
            'typing' => false
        ]);
    }
}
