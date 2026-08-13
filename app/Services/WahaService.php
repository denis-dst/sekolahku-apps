<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WahaService
{
    protected string $url;
    protected ?string $apiKey;
    protected string $session;
    protected string $senderPhone;

    public function __construct(?string $url = null, ?string $apiKey = null, ?string $session = null)
    {
        $baseUrl = $url ?: config('services.waha.url', env('WAHA_BASE_URL', 'http://127.0.0.1:3000'));
        // Ensure base URL doesn't end with slash
        $baseUrl = rtrim($baseUrl, '/');
        
        $this->url = str_ends_with($baseUrl, '/api/sendText') ? $baseUrl : $baseUrl . '/api/sendText';
        $this->apiKey = $apiKey ?: config('services.waha.api_key', env('WAHA_API_KEY'));
        $this->session = $session ?: config('services.waha.session', env('WAHA_SESSION', 'default'));
        $this->senderPhone = config('services.waha.sender_phone', env('WAHA_SENDER_PHONE', '6283878537818'));
    }

    /**
     * Format phone number to WAHA chatId format (e.g. 6283878537818@c.us)
     */
    public function formatChatId(string $phone): string
    {
        if (str_contains($phone, '@c.us') || str_contains($phone, '@g.us')) {
            return $phone;
        }

        // Clean digits
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return $digits . '@c.us';
    }

    /**
     * Send WhatsApp Message via WAHA HTTP API (POST /api/sendText)
     */
    public function sendMessage(string $target, string $message, ?string $session = null, ?string $token = null): array
    {
        $chatId = $this->formatChatId($target);
        $activeSession = $session ?: $this->session;

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $apiKey = $token ?: $this->apiKey;
        if (!empty($apiKey)) {
            $headers['X-Api-Key'] = $apiKey;
        }

        $payload = [
            'chatId' => $chatId,
            'text' => $message,
            'session' => $activeSession,
        ];

        try {
            $response = Http::withHeaders($headers)->post($this->url, $payload);

            Log::info("WAHA WA sent to {$chatId} (Session: {$activeSession}): " . $response->body());

            return [
                'status' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error("WAHA WA Exception: " . $e->getMessage());
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Send Absence Notification to Parent via WAHA
     */
    public function sendAbsenceAlert(string $parentPhone, string $studentName, string $date, string $status, ?string $token = null): array
    {
        $message = "*PEMBERITAHUAN PRESENSI SEKOLAHKU*\n\n" .
            "Yth. Orang Tua/Wali dari *{$studentName}*,\n\n" .
            "Memberitahukan bahwa putra/putri Anda tercatat *{$status}* pada tanggal *{$date}*.\n\n" .
            "Jika ada keterangan izin atau sakit yang belum disampaikan, mohon menghubungi wali kelas.\n\n" .
            "Pengirim: {$this->senderPhone}\n" .
            "Terima kasih.\n_SekolahKu System_";

        return $this->sendMessage($parentPhone, $message, null, $token);
    }

    /**
     * Send SPP Payment Receipt Notification via WAHA
     */
    public function sendPaymentReceipt(string $parentPhone, string $studentName, string $month, string $nominal, string $status, ?string $token = null): array
    {
        $message = "*BUKTI VERIFIKASI PEMBAYARAN SPP*\n\n" .
            "Yth. Orang Tua/Wali dari *{$studentName}*,\n\n" .
            "Pembayaran SPP bulan *{$month}* sebesar *Rp " . number_format((float)$nominal, 0, ',', '.') . "* telah *" . strtoupper($status) . "*.\n\n" .
            "Terima kasih atas partisipasi Anda dalam mendukung kelancaran operasional sekolah.\n\n" .
            "Pengirim: {$this->senderPhone}\n" .
            "_SekolahKu Digital Finance_";

        return $this->sendMessage($parentPhone, $message, null, $token);
    }
}
