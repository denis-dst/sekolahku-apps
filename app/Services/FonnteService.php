<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected string $url;
    protected ?string $token;

    public function __construct(?string $token = null)
    {
        $this->url = config('services.fonnte.url', 'https://api.fonnte.com/send');
        $this->token = $token ?: config('services.fonnte.token', env('FONNTE_TOKEN'));
    }

    /**
     * Send WhatsApp Message via Fonnte
     */
    public function sendMessage(string $target, string $message, ?string $token = null): array
    {
        $activeToken = $token ?: $this->token;

        if (empty($activeToken)) {
            Log::warning("Fonnte WhatsApp Token is empty. Message to {$target} suppressed.");
            return [
                'status' => false,
                'message' => 'Fonnte token missing. Message not sent.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $activeToken,
            ])->post($this->url, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62',
            ]);

            Log::info("Fonnte WA sent to {$target}: " . $response->body());

            return [
                'status' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error("Fonnte WA Exception: " . $e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send Absence Notification to Parent
     */
    public function sendAbsenceAlert(string $parentPhone, string $studentName, string $date, string $status, ?string $token = null): array
    {
        $message = "*PEMBERITAHUAN PRESENSI SEKOLAHKU*\n\n" .
            "Yth. Orang Tua/Wali dari *{$studentName}*,\n\n" .
            "Memberitahukan bahwa putra/putri Anda tercatat *{$status}* pada tanggal *{$date}*.\n\n" .
            "Jika ada keterangan izin atau sakit yang belum disampaikan, mohon menghubungi wali kelas.\n\n" .
            "Terima kasih.\n_SekolahKu System_";

        return $this->sendMessage($parentPhone, $message, $token);
    }

    /**
     * Send SPP Payment Receipt Notification
     */
    public function sendPaymentReceipt(string $parentPhone, string $studentName, string $month, string $nominal, string $status, ?string $token = null): array
    {
        $message = "*BUKTI VERIFIKASI PEMBAYARAN SPP*\n\n" .
            "Yth. Orang Tua/Wali dari *{$studentName}*,\n\n" .
            "Pembayaran SPP bulan *{$month}* sebesar *Rp " . number_format($nominal, 0, ',', '.') . "* telah *" . strtoupper($status) . "*.\n\n" .
            "Terima kasih atas partisipasi Anda dalam mendukung kelancaran operasional sekolah.\n\n" .
            "_SekolahKu Digital Finance_";

        return $this->sendMessage($parentPhone, $message, $token);
    }
}
