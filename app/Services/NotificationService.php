<?php

namespace App\Services;

use App\Models\MessageTemplate;
use App\Models\Rental;
use Carbon\Carbon;

class NotificationService
{
    protected $whatsappNotificationService;
    public function __construct(WhatsappNotificationService $whatsappNotificationService)
    {
        $this->whatsappNotificationService = $whatsappNotificationService;
    }

    /**
     * Fungsi utama untuk memproses dan mengirim notifikasi
     *
     * @param string $templateType
     * @param Rental $rental
     * @param array $additionalReplacements
     * @return bool
     */
    protected function processAndSendNotification($templateType, Rental $rental, array $additionalReplacements = [])
    {
        // Ambil template pesan berdasarkan tipe
        $template = MessageTemplate::where('type', $templateType)->first();

        if (!$template) {
            return false;
        }

        // Definisikan replacements dasar yang umum digunakan
        $baseReplacements = [
            '{car_name}' => $rental->car->brand . ' ' . $rental->car->model,
            '{user_name}' => $rental->user->name,
            '{total_fee}' => number_format($rental->total_fee, 2) . ' IDR'
        ];

        // Gabungkan replacements dasar dengan tambahan
        $replacements = array_merge($baseReplacements, $additionalReplacements);

        // Ganti placeholder dalam template
        $message = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $template->content
        );

        // Kirim pesan WhatsApp menggunakan template yang sudah diubah
        return $this->whatsappNotificationService->sendNotification($rental, $message);
    }

    /**
     * Kirim notifikasi WhatsApp untuk pemesanan rental baru
     *
     * @param Rental $rental
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return bool
     */
    public function sendRentalNotification(Rental $rental, Carbon $startDate, Carbon $endDate)
    {
        $additionalReplacements = [
            '{start_date}' => $startDate->format('d-m-Y'),
            '{end_date}' => $endDate->format('d-m-Y'),
        ];

        return $this->processAndSendNotification('sewa', $rental, $additionalReplacements);
    }

    /**
     * Kirim notifikasi WhatsApp untuk pembayaran berhasil/rental disetujui
     *
     * @param Rental $rental
     * @param string|null $reference
     * @return bool
     */
    public function sendApprovedNotification(Rental $rental, $reference = null)
    {
        $additionalReplacements = [];

        // Tambahkan reference pembayaran jika ada
        if ($reference) {
            $additionalReplacements['{payment_reference}'] = $reference;
        }

        return $this->processAndSendNotification('approved', $rental, $additionalReplacements);
    }

    /**
     * Kirim notifikasi WhatsApp untuk rental ditolak
     *
     * @param Rental $rental
     * @param string $reason
     * @return bool
     */
    public function sendRejectedNotification(Rental $rental, $reason = null)
    {
        $additionalReplacements = [];

        // Tambahkan alasan penolakan jika ada
        if ($reason) {
            $additionalReplacements['{rejection_reason}'] = $reason;
        }

        return $this->processAndSendNotification('rejected', $rental, $additionalReplacements);
    }

    /**
     * Kirim notifikasi WhatsApp untuk pengembalian mobil tepat waktu
     *
     * @param Rental $rental
     * @return bool
     */
    public function sendReturnOnTimeNotification(Rental $rental)
    {
        $returnDate = Carbon::parse($rental->return_date);

        $additionalReplacements = [
            '{return_date}' => $returnDate->format('d-m-Y'),
        ];

        return $this->processAndSendNotification('return_on_time', $rental, $additionalReplacements);
    }

    /**
     * Kirim notifikasi WhatsApp untuk pengembalian mobil terlambat
     *
     * @param Rental $rental
     * @param float $lateFee
     * @return bool
     */
    public function sendLateReturnNotification(Rental $rental, $lateFee)
    {
        $returnDate = Carbon::parse($rental->return_date);
        $endDate = Carbon::parse($rental->rental_end_date);
        $lateDays = $endDate->diffInDays($returnDate);

        $additionalReplacements = [
            '{return_date}' => $returnDate->format('d-m-Y'),
            '{end_date}' => $endDate->format('d-m-Y'),
            '{late_days}' => $lateDays,
            '{late_fee}' => number_format($lateFee, 2) . ' IDR',
        ];

        return $this->processAndSendNotification('return_late', $rental, $additionalReplacements);
    }
}
