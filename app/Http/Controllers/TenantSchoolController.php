<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantSchoolController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            abort(403, 'Akses terbatas untuk pengguna Yayasan/Tenant.');
        }

        $schools = $tenant->schools()->withCount(['siswas', 'gurus', 'rombels'])->get();
        $plan = $tenant->subscriptionPlan;
        $maxSchools = $plan?->max_schools ?? 1;

        return view('schools.index', compact('tenant', 'schools', 'plan', 'maxSchools'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            abort(403, 'Akses terbatas untuk pengguna Yayasan/Tenant.');
        }

        if (!$tenant->canAddSchool()) {
            $max = $tenant->subscriptionPlan?->max_schools ?? 1;
            return redirect()->back()->with('error', "Batas unit sekolah pada paket {$tenant->subscriptionPlan?->name} Anda telah tercapai (Maksimal {$max} unit sekolah). Silakan hubungi Superadmin untuk upgrade lisensi.");
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'jenjang' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'kepala_sekolah_nama' => 'nullable|string|max:255',
            'bendahara_nama' => 'nullable|string|max:255',
        ]);

        School::create([
            'tenant_id' => $tenant->id,
            'name' => $request->name,
            'npsn' => $request->npsn,
            'jenjang' => $request->jenjang,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'kepala_sekolah_nama' => $request->kepala_sekolah_nama,
            'bendahara_nama' => $request->bendahara_nama,
            'status' => 'active',
        ]);

        return redirect()->route('schools.index')->with('success', 'Unit sekolah baru ' . $request->name . ' berhasil didaftarkan ke dalam ekosistem Yayasan!');
    }

    public function switchSchool(School $school)
    {
        $user = Auth::user();
        
        // Ensure school belongs to user's tenant
        if ($school->tenant_id !== $user->tenant_id && !$user->hasRole('Superadmin')) {
            abort(403, 'Unit sekolah ini bukan milik Yayasan Anda.');
        }

        $user->update(['school_id' => $school->id]);

        return redirect()->back()->with('success', 'Konteks sekolah aktif berhasil dialihkan ke ' . $school->name);
    }
}
