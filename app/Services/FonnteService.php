<?php

namespace App\Services;

class FonnteService
{
    protected WahaService $waha;

    public function __construct(?string $token = null)
    {
        $this->waha = new WahaService(null, $token);
    }

    public function sendMessage(string $target, string $message, ?string $token = null): array
    {
        return $this->waha->sendMessage($target, $message, null, $token);
    }

    public function sendAbsenceAlert(string $parentPhone, string $studentName, string $date, string $status, ?string $token = null): array
    {
        return $this->waha->sendAbsenceAlert($parentPhone, $studentName, $date, $status, $token);
    }

    public function sendPaymentReceipt(string $parentPhone, string $studentName, string $month, string $nominal, string $status, ?string $token = null): array
    {
        return $this->waha->sendPaymentReceipt($parentPhone, $studentName, $month, $nominal, $status, $token);
    }
}
