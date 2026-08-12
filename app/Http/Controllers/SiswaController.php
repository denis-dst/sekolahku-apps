<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rombel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
            $query->where('nama_lengkap', 'like', '%' . $request->search . '%')
                ->orWhere('nisn', 'like', '%' . $request->search . '%');
        }

        $siswas = $query->paginate(15);

        return view('siswa.index', compact('siswas', 'rombels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:50',
            'rombel_id' => 'nullable|exists:rombels,id',
            'jenis_kelamin' => 'required|in:L,P',
            'nama_ortu' => 'nullable|string',
            'no_hp_ortu' => 'nullable|string',
            'email_siswa' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $siswaUser = null;

        // Optionally create student account for Absen Mandiri
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
}
