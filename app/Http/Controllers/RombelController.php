<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RombelController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $rombels = Rombel::where('school_id', $schoolId)->with(['waliKelas:id,nama_lengkap', 'tahunAjaran:id,name'])->withCount('siswas')->get();
        $gurus = Guru::where('school_id', $schoolId)->select('id', 'nama_lengkap')->get();
        $tahunAjarans = TahunAjaran::where('school_id', $schoolId)->select('id', 'name')->get();

        return view('rombel.index', compact('rombels', 'gurus', 'tahunAjarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_rombel' => 'required|string|max:255',
            'tingkat' => 'required|string',
            'guru_id' => 'nullable|exists:gurus,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ]);

        Rombel::create([
            'school_id' => Auth::user()->school_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'guru_id' => $request->guru_id,
            'nama_rombel' => $request->nama_rombel,
            'tingkat' => $request->tingkat,
        ]);

        return redirect()->back()->with('success', 'Rombel berhasil dibuat!');
    }
}
