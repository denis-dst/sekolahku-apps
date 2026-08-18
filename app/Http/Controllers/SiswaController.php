<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::where('school_id', $schoolId)->get();

        $query = Siswa::where('school_id', $schoolId)->with('rombel');

        if ($request->filled('rombel_id')) {
            $query->where('rombel_id', $request->rombel_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $siswas = $query->latest()->paginate(15)->withQueryString();

        return view('siswa.index', compact('siswas', 'rombels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:50',
            'nik' => 'nullable|string|max:50',
            'rombel_id' => 'nullable|exists:rombels,id',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ortu' => 'nullable|string|max:255',
            'no_hp_ortu' => 'nullable|string|max:50',
            'email_siswa' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $school = $user->school;

        if (!$school->canAddSiswa()) {
            return redirect()->back()->with('error', 'Batas kuota siswa untuk paket langganan sekolah Anda telah tercapai. Silakan upgrade paket.');
        }

        $siswaUser = null;
        if ($request->filled('email_siswa')) {
            $siswaUser = User::create([
                'tenant_id' => $user->tenant_id,
                'school_id' => $user->school_id,
                'name' => $request->nama_lengkap,
                'email' => $request->email_siswa,
                'phone' => $request->no_hp_ortu,
                'password' => Hash::make($request->password ?: '12345678'),
                'is_active' => true,
            ]);
            $siswaUser->assignRole('Siswa');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_siswa', 'public');
        }

        Siswa::create([
            'school_id' => $user->school_id,
            'user_id' => $siswaUser?->id,
            'rombel_id' => $request->rombel_id,
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'nama_panggilan' => $request->nama_panggilan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'nama_ortu' => $request->nama_ortu,
            'no_hp_ortu' => $request->no_hp_ortu,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            'status' => 'Aktif',
        ]);

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }

    /**
     * Download Excel template for Siswa import
     */
    public function downloadTemplate()
    {
        $school = Auth::user()->school;
        $firstRombel = Rombel::where('school_id', $school->id)->first();
        $rombelName = $firstRombel ? $firstRombel->nama_rombel : 'Kelompok A';

        $filename = 'Template_Import_Siswa_' . str_replace(' ', '_', $school->name) . '.xlsx';

        return Excel::download(new SiswaTemplateExport($rombelName), $filename);
    }

    /**
     * Bulk Import Siswa data from Excel / CSV file
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'fallback_rombel_id' => 'nullable|exists:rombels,id',
        ]);

        $school = Auth::user()->school;

        if (!$school->canAddSiswa()) {
            return redirect()->back()->with('error', 'Batas kuota siswa untuk paket langganan sekolah Anda telah tercapai. Silakan upgrade paket.');
        }

        $import = new SiswaImport($school, $request->fallback_rombel_id);

        try {
            Excel::import($import, $request->file('file'));

            $message = "Proses import selesai: {$import->importedCount} siswa berhasil ditambahkan.";
            if ($import->skippedCount > 0) {
                $message .= " ({$import->skippedCount} baris kosong/tidak valid dilewati).";
            }

            if (!empty($import->errors)) {
                return redirect()->back()->with('warning', $message . ' ' . implode(' ', $import->errors));
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengimpor data Excel: ' . $e->getMessage());
        }
    }
}
