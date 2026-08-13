<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WahaSettingController extends Controller
{
    public function index()
    {
        $wahaUrl = config('services.waha.url', env('WAHA_BASE_URL', 'http://127.0.0.1:3000'));
        $wahaApiKey = config('services.waha.api_key', env('WAHA_API_KEY'));
        $wahaSession = config('services.waha.session', env('WAHA_SESSION', 'default'));
        $wahaSenderPhone = config('services.waha.sender_phone', env('WAHA_SENDER_PHONE', '6283878537818'));

        return view('superadmin.waha.index', compact('wahaUrl', 'wahaApiKey', 'wahaSession', 'wahaSenderPhone'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'waha_url' => 'required|url',
            'waha_api_key' => 'nullable|string',
            'waha_session' => 'required|string',
            'waha_sender_phone' => 'required|string',
        ]);

        $this->updateEnv([
            'WAHA_BASE_URL' => rtrim($request->waha_url, '/'),
            'WAHA_API_KEY' => $request->waha_api_key ?? '',
            'WAHA_SESSION' => $request->waha_session,
            'WAHA_SENDER_PHONE' => preg_replace('/[^0-9]/', '', $request->waha_sender_phone),
        ]);

        return redirect()->route('admin.waha.index')->with('success', 'Konfigurasi WAHA Server berhasil diperbarui!');
    }

    public function testConnection(Request $request)
    {
        $baseUrl = rtrim($request->input('waha_url', env('WAHA_BASE_URL', 'http://localhost:3000')), '/');
        $apiKey = $request->input('waha_api_key', env('WAHA_API_KEY'));

        $headers = [
            'Accept' => 'application/json',
        ];

        if (!empty($apiKey)) {
            $headers['X-Api-Key'] = $apiKey;
        }

        try {
            // Test pinging sessions endpoint in WAHA
            $response = Http::withHeaders($headers)->timeout(5)->get($baseUrl . '/api/sessions');

            if ($response->successful()) {
                $sessions = $response->json();
                return redirect()->route('admin.waha.index')->with('success', 'Koneksi ke WAHA Server BERHASIL! (Sesi Aktif: ' . count($sessions) . ' sesi)');
            }

            return redirect()->route('admin.waha.index')->with('error', 'Gagal terhubung ke WAHA Server (HTTP ' . $response->status() . '): ' . $response->body());
        } catch (\Exception $e) {
            Log::error("WAHA Test Connection Exception: " . $e->getMessage());
            return redirect()->route('admin.waha.index')->with('error', 'Koneksi gagal: Tidak dapat menjangkau server WAHA di ' . $baseUrl . ' (' . $e->getMessage() . ')');
        }
    }

    protected function updateEnv(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $keyPattern = "/^{$key}=.*/m";
            if (preg_match($keyPattern, $envContent)) {
                $envContent = preg_replace($keyPattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
