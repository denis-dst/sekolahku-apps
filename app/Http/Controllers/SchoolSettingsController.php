<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolSettingsController extends Controller
{
    public function edit()
    {
        $school = Auth::user()->school;
        return view('settings.school', compact('school'));
    }

    public function update(Request $request)
    {
        $school = Auth::user()->school;

        $request->validate([
            'name' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'jenjang' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fonnte_token' => 'nullable|string',
            'bank_name_1' => 'nullable|string',
            'bank_acc_1' => 'nullable|string',
            'bank_holder_1' => 'nullable|string',
            'bank_name_2' => 'nullable|string',
            'bank_acc_2' => 'nullable|string',
            'bank_holder_2' => 'nullable|string',
            'kepala_sekolah_nama' => 'nullable|string',
            'kepala_sekolah_nip' => 'nullable|string',
            'bendahara_nama' => 'nullable|string',
            'bendahara_nip' => 'nullable|string',
        ]);

        $data = $request->only([
            'name', 'npsn', 'jenjang', 'address', 'phone', 'email', 'fonnte_token',
            'kepala_sekolah_nama', 'kepala_sekolah_nip', 'bendahara_nama', 'bendahara_nip'
        ]);

        if ($request->hasFile('qris_image')) {
            if ($school->qris_image) {
                Storage::disk('public')->delete($school->qris_image);
            }
            $data['qris_image'] = $request->file('qris_image')->store('qris', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Format bank accounts
        $bankAccounts = [];
        if ($request->filled('bank_name_1')) {
            $bankAccounts[] = [
                'bank' => $request->bank_name_1,
                'account_number' => $request->bank_acc_1,
                'account_name' => $request->bank_holder_1,
            ];
        }
        if ($request->filled('bank_name_2')) {
            $bankAccounts[] = [
                'bank' => $request->bank_name_2,
                'account_number' => $request->bank_acc_2,
                'account_name' => $request->bank_holder_2,
            ];
        }
        $data['bank_accounts'] = $bankAccounts;

        $school->update($data);

        return redirect()->back()->with('success', 'Pengaturan profil sekolah & QRIS pembayaran berhasil diperbarui!');
    }
}
