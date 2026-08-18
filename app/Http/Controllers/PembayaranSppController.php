<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\PembayaranSpp;
use App\Services\WahaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranSppController extends Controller
{
    protected WahaService $waha;

    public function __construct(WahaService $waha)
    {
        $this->waha = $waha;
    }

    /**
     * Parent Upload Payment Proof (Manual QRIS / Transfer Bank)
     */
    public function uploadBukti(Request $request, TagihanSpp $tagihan)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:Manual QRIS,Transfer Bank,Cash',
            'nominal_bayar' => 'required|numeric|min:1',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,heic|max:5120',
        ]);

        $file = $request->file('bukti_pembayaran');

        if (!$file || !$file->isValid() || empty($file->getRealPath())) {
            return redirect()->back()->with('error', 'File bukti pembayaran gagal diunggah. Pastikan file berupa gambar valid dan tidak rusak.');
        }

        try {
            $filePath = $file->store('bukti_spp', 'public');
        } catch (\Throwable $e) {
            Storage::disk('public')->makeDirectory('bukti_spp');
            $filePath = $file->store('bukti_spp', 'public');
        }

        $user = Auth::user();

        $pembayaran = PembayaranSpp::create([
            'tagihan_spp_id' => $tagihan->id,
            'school_id' => $tagihan->school_id,
            'siswa_id' => $tagihan->siswa_id,
            'user_id' => $user->id,
            'tanggal_bayar' => now(),
            'nominal_bayar' => $request->nominal_bayar,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bukti_pembayaran' => $filePath,
            'status_verifikasi' => 'Pending',
        ]);

        // Update tagihan status to Menunggu Verifikasi
        $tagihan->update(['status' => 'Menunggu Verifikasi']);

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi dari Bendahara Sekolah.');
    }

    /**
     * Bendahara Verification Queue
     */
    public function verifikasiQueue()
    {
        $schoolId = Auth::user()->school_id;
        $pendingPayments = PembayaranSpp::where('school_id', $schoolId)
            ->where('status_verifikasi', 'Pending')
            ->with(['tagihanSpp', 'siswa', 'user'])
            ->latest()
            ->get();

        return view('spp.verifikasi', compact('pendingPayments'));
    }

    /**
     * Bendahara Approve / Reject Payment Proof
     */
    public function verifikasiStore(Request $request, PembayaranSpp $pembayaran)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Approved,Rejected',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $user = Auth::user();
        $school = $user->school;
        $tagihan = $pembayaran->tagihanSpp;

        $pembayaran->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_verifikasi' => $request->catatan_verifikasi,
            'user_id' => $user->id, // Verifier
        ]);

        if ($request->status_verifikasi === 'Approved') {
            $tagihan->update(['status' => 'Lunas']);

            // Send WAHA WhatsApp Digital Receipt to Parent
            $siswa = $pembayaran->siswa;
            if ($siswa && $siswa->no_hp_ortu) {
                $this->waha->sendPaymentReceipt(
                    $siswa->no_hp_ortu,
                    $siswa->nama_lengkap,
                    $tagihan->bulan . ' ' . $tagihan->tahun,
                    $pembayaran->nominal_bayar,
                    'LUNAS',
                    $school->fonnte_token
                );
            }

            $msg = 'Pembayaran SPP berhasil disetujui & ditandai Lunas! Bukti via WhatsApp (WAHA) telah dikirimkan.';
        } else {
            $tagihan->update(['status' => 'Belum Lunas']);
            $msg = 'Pembayaran SPP ditolak dengan alasan: ' . $request->catatan_verifikasi;
        }

        return redirect()->back()->with('success', $msg);
    }
}
