<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $gurus = Guru::where('school_id', $schoolId)->with('user')->paginate(15);
        return view('guru.index', compact('gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'nip' => 'nullable|string',
            'no_hp' => 'nullable|string',
        ]);

        $authUser = Auth::user();

        $user = User::create([
            'tenant_id' => $authUser->tenant_id,
            'school_id' => $authUser->school_id,
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'phone' => $request->no_hp,
            'password' => Hash::make($request->password ?: 'password'),
            'is_active' => true,
        ]);
        $user->assignRole('Guru');

        Guru::create([
            'school_id' => $authUser->school_id,
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nuptk' => $request->nuptk,
            'nama_lengkap' => $request->nama_lengkap,
            'gelar' => $request->gelar,
            'jenis_kelamin' => $request->jenis_kelamin ?: 'L',
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Data guru baru berhasil disimpan & akun login dibuat!');
    }
}
