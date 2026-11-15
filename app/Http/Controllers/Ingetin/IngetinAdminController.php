<?php

namespace App\Http\Controllers\Ingetin;

use App\Http\Controllers\Controller;
use App\Models\Ingetin\IngetinSetting;
use App\Models\Ingetin\IngetinDzikir;
use App\Models\Ingetin\IngetinHadith;
use App\Models\Ingetin\IngetinDonationStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class IngetinAdminController extends Controller
{
    private function isAuthenticated(): bool
    {
        return session('ingetin_admin_logged') === true || Auth::check();
    }

    /**
     * GET /ingetin/login
     */
    public function showLogin()
    {
        if ($this->isAuthenticated()) {
            return redirect()->route('ingetin.dashboard');
        }
        return view('ingetin.login');
    }

    /**
     * POST /ingetin/login
     */
    public function login(Request $request)
    {
        $currentPin = IngetinSetting::getVal('admin_pin', '123456');

        // Check if login via PIN
        if ($request->filled('pin')) {
            $inputPin = trim($request->input('pin'));
            if ($inputPin === $currentPin) {
                session(['ingetin_admin_logged' => true]);
                return redirect()->route('ingetin.dashboard')->with('success', 'Selamat datang di Panel Admin Ingetin!');
            }
            return back()->with('error', 'PIN Admin salah! (Default PIN: 123456)')->withInput();
        }

        // Check if login via SekolahKu credentials
        if ($request->filled('email') && $request->filled('password')) {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                session(['ingetin_admin_logged' => true]);
                return redirect()->route('ingetin.dashboard')->with('success', 'Berhasil login melalui Akun SekolahKu!');
            }
            return back()->with('error', 'Email atau password SekolahKu salah!')->withInput();
        }

        return back()->with('error', 'Silakan masukkan PIN atau kredensial login.');
    }

    /**
     * POST & GET /ingetin/logout
     */
    public function logout(Request $request)
    {
        session()->forget('ingetin_admin_logged');
        return redirect()->route('ingetin.login')->with('info', 'Anda telah keluar dari Panel Ingetin.');
    }

    /**
     * GET /ingetin & /ingetin/dashboard
     */
    public function dashboard(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $stats = IngetinDonationStat::first() ?? new IngetinDonationStat([
            'total_donors' => 2847,
            'total_amount_text' => '47.2jt',
            'total_programs' => 12,
            'note' => 'Seluruh donasi disalurkan 100% tanpa potongan biaya administrasi.'
        ]);

        $settings = [
            'app_name' => IngetinSetting::getVal('app_name', 'Ingetin'),
            'app_version' => IngetinSetting::getVal('app_version', '1.0.0'),
            'merchant_name' => IngetinSetting::getVal('merchant_name', 'Ingetin Sedekah Peduli'),
            'merchant_city' => IngetinSetting::getVal('merchant_city', 'Jakarta Selatan'),
            'donation_info' => IngetinSetting::getVal('donation_info', 'Sedekah tidak akan mengurangi harta. Semoga menjadi amal jariyah.'),
            'qris_image_url' => IngetinSetting::getVal('qris_image_url', 'uploads/ingetin/qris_default.png'),
            'dhuha_start_time' => IngetinSetting::getVal('dhuha_start_time', '07:00'),
            'dhuha_end_time' => IngetinSetting::getVal('dhuha_end_time', '11:00'),
            'admin_pin' => IngetinSetting::getVal('admin_pin', '123456'),
        ];

        $rawQris = $settings['qris_image_url'];
        $qrisPreviewUrl = (str_starts_with($rawQris, 'http://') || str_starts_with($rawQris, 'https://'))
            ? $rawQris
            : asset(ltrim($rawQris, '/'));

        $allDzikir = IngetinDzikir::orderBy('category', 'asc')->orderBy('order_index', 'asc')->orderBy('id', 'asc')->get();
        $allHadith = IngetinHadith::orderBy('id', 'asc')->get();

        return view('ingetin.dashboard', compact(
            'stats',
            'settings',
            'qrisPreviewUrl',
            'allDzikir',
            'allHadith'
        ));
    }

    /**
     * POST /ingetin/settings/general
     */
    public function updateSettings(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        if ($request->filled('app_name')) {
            IngetinSetting::setVal('app_name', trim($request->input('app_name')));
        }
        if ($request->filled('merchant_name')) {
            IngetinSetting::setVal('merchant_name', trim($request->input('merchant_name')));
        }
        if ($request->filled('merchant_city')) {
            IngetinSetting::setVal('merchant_city', trim($request->input('merchant_city')));
        }
        if ($request->filled('donation_info')) {
            IngetinSetting::setVal('donation_info', trim($request->input('donation_info')));
        }
        if ($request->filled('dhuha_start_time')) {
            IngetinSetting::setVal('dhuha_start_time', trim($request->input('dhuha_start_time')));
        }
        if ($request->filled('dhuha_end_time')) {
            IngetinSetting::setVal('dhuha_end_time', trim($request->input('dhuha_end_time')));
        }

        // Upload QRIS file
        if ($request->hasFile('qris_file') && $request->file('qris_file')->isValid()) {
            $file = $request->file('qris_file');
            $ext = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                $uploadDir = public_path('uploads/ingetin');
                if (!File::exists($uploadDir)) {
                    File::makeDirectory($uploadDir, 0755, true);
                }
                $filename = 'qris_merchant_' . time() . '.' . $ext;
                $file->move($uploadDir, $filename);
                IngetinSetting::setVal('qris_image_url', 'uploads/ingetin/' . $filename);
            } else {
                return back()->with('error', 'Format gambar harus PNG, JPG, JPEG, atau WebP!');
            }
        }

        return back()->with('success', 'Pengaturan QRIS & Informasi berhasil disimpan!');
    }

    /**
     * POST /ingetin/settings/stats
     */
    public function updateStats(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        $donors = (int)$request->input('total_donors', 0);
        $amount = trim($request->input('total_amount_text', ''));
        $programs = (int)$request->input('total_programs', 0);
        $note = trim($request->input('note', ''));

        $stat = IngetinDonationStat::first() ?? new IngetinDonationStat(['id' => 1]);
        $stat->total_donors = $donors;
        $stat->total_amount_text = $amount;
        $stat->total_programs = $programs;
        if (!empty($note)) {
            $stat->note = $note;
        }
        $stat->save();

        return back()->with('success', 'Statistik sedekah berhasil diperbarui!');
    }

    /**
     * POST /ingetin/dzikir/store
     */
    public function storeDzikir(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'arabic' => 'required|string',
            'category' => 'required|in:pagi,petang,tidur,lainnya',
        ]);

        $id = (int)$request->input('id', 0);
        $data = [
            'category' => $request->input('category'),
            'title' => $request->input('title'),
            'arabic' => $request->input('arabic'),
            'latin' => (string)$request->input('latin', ''),
            'translation' => (string)$request->input('translation', ''),
            'target_count' => max(1, (int)$request->input('target_count', 1)),
            'benefit' => (string)$request->input('benefit', ''),
            'source' => (string)$request->input('source', ''),
            'order_index' => (int)$request->input('order_index', 0),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($id > 0) {
            IngetinDzikir::where('id', $id)->update($data);
            $msg = 'Dzikir berhasil diperbarui!';
        } else {
            IngetinDzikir::create($data);
            $msg = 'Dzikir baru berhasil ditambahkan!';
        }

        return back()->with('success', $msg);
    }

    /**
     * POST /ingetin/dzikir/{id}/delete
     */
    public function deleteDzikir($id)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        IngetinDzikir::where('id', $id)->delete();
        return back()->with('success', 'Dzikir berhasil dihapus!');
    }

    /**
     * POST /ingetin/hadith/store
     */
    public function storeHadith(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        $request->validate([
            'text' => 'required|string',
            'narrator' => 'required|string|max:255',
        ]);

        $id = (int)$request->input('id', 0);
        $data = [
            'text' => $request->input('text'),
            'narrator' => $request->input('narrator'),
            'topic' => (string)$request->input('topic', ''),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($id > 0) {
            IngetinHadith::where('id', $id)->update($data);
            $msg = 'Hadits berhasil diperbarui!';
        } else {
            IngetinHadith::create($data);
            $msg = 'Hadits baru berhasil ditambahkan!';
        }

        return back()->with('success', $msg);
    }

    /**
     * POST /ingetin/hadith/{id}/delete
     */
    public function deleteHadith($id)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        IngetinHadith::where('id', $id)->delete();
        return back()->with('success', 'Hadits berhasil dihapus!');
    }

    /**
     * POST /ingetin/pin/update
     */
    public function updatePin(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('ingetin.login');
        }

        $newPin = trim($request->input('new_pin', ''));
        if (strlen($newPin) < 4) {
            return back()->with('error', 'PIN Admin minimal harus 4 digit!');
        }

        IngetinSetting::setVal('admin_pin', $newPin);
        return back()->with('success', 'PIN Admin berhasil diubah!');
    }
}
