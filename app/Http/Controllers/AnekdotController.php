<?php

namespace App\Http\Controllers;

use App\Models\Anekdot;
use App\Models\AnekdotLampiran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnekdotController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $user = Auth::user();

        $query = Anekdot::where('school_id', $schoolId)->with(['siswa', 'guru', 'lampirans']);

        if ($user->hasRole('Orang Tua')) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        } elseif ($user->hasRole('Siswa')) {
            $siswa = $user->siswa;
            if ($siswa) {
                $query->where('siswa_id', $siswa->id);
            }
        }

        $anekdots = $query->latest()->paginate(10);
        $siswas = Siswa::where('school_id', $schoolId)->where('status', 'Aktif')->get();

        return view('anekdot.index', compact('anekdots', 'siswas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'tanggal' => 'required|date',
            'peristiwa' => 'required|string',
            'analisis_capaian' => 'nullable|string',
            'umpan_balik' => 'nullable|string',
            'lampirans.*' => 'nullable|file|mimes:jpg,png,jpeg,pdf|max:3072',
        ]);

        $user = Auth::user();
        $guru = $user->guru;

        $anekdot = Anekdot::create([
            'school_id' => $user->school_id,
            'siswa_id' => $request->siswa_id,
            'guru_id' => $guru?->id,
            'tanggal' => $request->tanggal,
            'peristiwa' => $request->peristiwa,
            'analisis_capaian' => $request->analisis_capaian,
            'umpan_balik' => $request->umpan_balik,
        ]);

        if ($request->hasFile('lampirans')) {
            foreach ($request->file('lampirans') as $file) {
                $path = $file->store('anekdot', 'public');
                AnekdotLampiran::create([
                    'anekdot_id' => $anekdot->id,
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Catatan Anekdot perkembangan siswa berhasil dicatat!');
    }
}
